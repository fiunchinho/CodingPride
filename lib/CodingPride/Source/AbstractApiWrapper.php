<?php
namespace CodingPride\Source;

abstract class AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME = '';

	protected $_dm;
	protected $http;
	protected $config;

	public function __construct( $database, $config, $http )
	{
		$this->_dm 					= $database;
		$this->config 				= $config;
		$this->http 				= $http;
		$this->setUpHttp();
	}

	public function getLatestCommits( $params = array(), $max = 1000 )
	{
		$commit_collection		= new \CodingPride\CommitList();
		$commit_repository		= $this->_dm->getRepository( 'CodingPride\Document\Commit' );

		$commit_revision_iterator		= $this->getCommitRevisionIterator( $params );
		$converter = $this->getConverter();

		foreach ( $commit_revision_iterator as $commit_info )
		{
			$commit_details 	= $this->http->get( $this->getCommitDetailsUrl( $converter->getRevision( $commit_info ) ) );
			$commit 			= $converter->convert( $commit_details );
			$is_commit_new		= $commit_repository->create( $commit );

			if ( $is_commit_new )
			{
				$commit_collection[] = $commit;	
			}
		}

		return $commit_collection;
	}

	abstract protected function setUpHttp();

	protected function getConverter()
	{
		$class_name = static::CONVERTER_CLASS_NAME;

		if ( empty( $class_name ) )
		{
			throw new \RuntimeException( 'You must exp...' );
		}

		return new $class_name();
	}
}
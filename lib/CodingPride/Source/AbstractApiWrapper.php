<?php
namespace CodingPride\Source;

abstract class AbstractApiWrapper
{
	const CONVERTER_CLASS_NAME = '';

	protected $_dm;
	protected $http;
	protected $config;

	abstract protected function getBaseUrl();

	public function __construct( $database, $config, $http )
	{
		$this->_dm 					= $database;
		$this->config 				= $config;
		$this->http 				= $http;
		$this->http->setBaseUrl( $this->getBaseUrl() );
	}

	public function getLatestCommits( $params = array(), $max = 1000 )
	{
		$commit_collection		= new \CodingPride\CommitList();
		$commit_repository		= $this->_dm->getRepository( 'CodingPride\Document\Commit' );

		$converter = $this->getConverter();
		$revisions = $this->getCommitRevisionIterator( $params );

		foreach ( $revisions as $revision )
		{
			$api_response 	= $this->getCommitDetailsApiResponse( $converter->getRevision( $revision ) );
			$commit 		= $converter->convert( $api_response );
			$is_commit_new	= $commit_repository->create( $commit );

			if ( $is_commit_new )
			{
				$commit_collection[] = $commit;	
			}
		}

		return $commit_collection;
	}

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
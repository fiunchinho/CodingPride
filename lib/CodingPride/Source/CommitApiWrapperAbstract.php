<?php
namespace CodingPride\Source;

abstract class AbstractApiWrapper
{
	private $_dm;
	private $http;
	private $config;

	public function __construct( $database, $config, $http = null )
	{
		$this->_dm 					= $database;
		$this->config 				= $config;
		
		if ( empty( $http ) )
		{
			$http = $this->getHttpClient();
		}

		$this->http 				= $http;
	}

	abstract protected function getHttpClient();

	abstract protected function getCommitListUrl();

	abstract protected function getCommitDetailsUrl();

	abstract protected function getConverter();

	abstract protected function getLatestCommits( $branch, $max_results );
}
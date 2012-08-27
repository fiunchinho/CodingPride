<?php
namespace CodingPride\Source;

abstract class AbstractApiWrapper
{
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

	abstract protected function setUpHttp();

	abstract protected function getConverter();

	abstract protected function getLatestCommits( $branch, $max_results );
}
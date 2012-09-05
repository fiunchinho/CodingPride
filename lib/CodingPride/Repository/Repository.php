<?php
namespace CodingPride\Repository;

abstract class Repository
{
	const CONVERTER_CLASS_NAME = '';

	protected $_dm;
	protected $config;

	abstract public function getCommits();

	public function __construct( $database, $config )
	{
		$this->_dm 					= $database;
		$this->config 				= $config;
	}

	protected function getConverter()
	{
		$class_name = static::CONVERTER_CLASS_NAME;

		if ( empty( $class_name ) )
		{
			throw new \RuntimeException( 'You must specified a converter class to convert the response into a Commit object.' );
		}

		return new $class_name();
	}
}
<?php
namespace CodingPride\Condition;

abstract class AbstractDatabaseAwareCondition implements ConditionInterface
{
	public function __construct( $database_manager )
	{
		$this->_dm = $database_manager;
	}
}
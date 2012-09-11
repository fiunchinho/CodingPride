<?php
namespace CodingPride\Condition;

class EarlyCommitCondition implements ConditionInterface
{
	const EARLY_TIME = '09:00';
	
	public function check( \CodingPride\Document\Commit $commit )
	{
		return ( $commit->getDate()->format( 'H:i' ) <= self::EARLY_TIME );
	}
}
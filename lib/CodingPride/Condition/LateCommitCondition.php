<?php
namespace CodingPride\Condition;

class LateCommitCondition implements ConditionInterface
{
	const LATE_TIME = '16:00';

	public function check( \CodingPride\Document\Commit $commit )
	{
		return ( $commit->getDate()->format( 'H:i' ) >= self::LATE_TIME );
	}
}
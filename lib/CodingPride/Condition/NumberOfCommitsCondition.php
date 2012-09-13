<?php
namespace CodingPride\Condition;

abstract class NumberOfCommitsCondition implements ConditionInterface
{
	protected $number_to_reach;

	public function check( \CodingPride\Document\Commit $commit )
	{
		return ( count( $commit->getAuthor()->getCommits() ) >= $this->number_to_reach );
	}
}
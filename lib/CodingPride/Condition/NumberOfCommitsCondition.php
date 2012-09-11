<?php
namespace CodingPride\Condition;

class NumberOfCommitsCondition implements ConditionInterface
{
	public $number_to_reach;

	public function check( \CodingPride\Document\Commit $commit )
	{
		return ( $commit->getAuthor()->getCommits() >= $this->number_to_reach );
	}
}
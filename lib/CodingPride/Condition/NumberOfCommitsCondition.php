<?php
namespace CodingPride\Condition;

//require_once 'ConditionInterface.php';

class NumberOfCommitsCondition implements ConditionInterface
{
	public $number_to_reach;

	public function check( \CodingPride\Document\Commit $commit )
	{
		$total_commits = $commit->getAuthor()->getTotalCommits();
		if ( $total_commits + 1 >= $this->number_to_reach )
		{
			return true;
		}
		return false;
	}
}
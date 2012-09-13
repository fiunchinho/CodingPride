<?php
namespace CodingPride\Tests\Condition;

class Reach1000CommitsConditionTest extends NumberOfCommitsCondition
{
	public function getConditionClassName()
	{
		return '\CodingPride\Condition\Reach1000CommitsCondition';
	}

	public function getEnoughCommitsToPass()
	{
		return range( 1, 1001 );
	}

	public function getNotEnoughCommitsToPass()
	{
		return range( 1, 2 );
	}
}
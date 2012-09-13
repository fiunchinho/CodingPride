<?php
namespace CodingPride\Tests\Condition;

class Reach500CommitsConditionTest extends NumberOfCommitsCondition
{
	public function getConditionClassName()
	{
		return '\CodingPride\Condition\Reach500CommitsCondition';
	}

	public function getEnoughCommitsToPass()
	{
		return range( 1, 501 );
	}

	public function getNotEnoughCommitsToPass()
	{
		return range( 1, 2 );
	}
}
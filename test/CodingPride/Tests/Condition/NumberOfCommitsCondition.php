<?php
namespace CodingPride\Tests\Condition;

abstract class NumberOfCommitsCondition extends \PHPUnit_Framework_TestCase
{
	abstract public function getConditionClassName();
	abstract public function getEnoughCommitsToPass();
	abstract public function getNotEnoughCommitsToPass();

	public function testConditionIsMetWhenTheNumberOfCommitsIsHigherThanLimit()
	{
		$author 	= $this->getMock( '\CodingPride\Document\User', array( 'getCommits' ) );
		$author->expects( $this->once() )->method( 'getCommits' )->will( $this->returnValue( $this->getEnoughCommitsToPass() ) );

		$commit 	= $this->getMock( '\CodingPride\Document\Commit', array( 'getAuthor' ) );
		$commit->expects( $this->once() )->method( 'getAuthor' )->will( $this->returnValue( $author ) );
		
		$condition_name = $this->getConditionClassName();
		$condition 	= new $condition_name();

		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when the number of commits is higher than the limit.' );
	}

	public function testConditionIsNotMetIfCommitsAreLessThanTheLimit()
	{
		$author 	= $this->getMock( '\CodingPride\Document\User', array( 'getCommits' ) );
		$author->expects( $this->once() )->method( 'getCommits' )->will( $this->returnValue( $this->getNotEnoughCommitsToPass() ) );

		$commit 	= $this->getMock( '\CodingPride\Document\Commit', array( 'getAuthor' ) );
		$commit->expects( $this->once() )->method( 'getAuthor' )->will( $this->returnValue( $author ) );
		
		$condition_name = $this->getConditionClassName();
		$condition 	= new $condition_name();

		$this->assertFalse( $condition->check( $commit ), 'The condition has NOT to be met when the number of commits is lower than the limit.' );
	}
}
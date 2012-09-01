<?php
namespace CodingPride\Tests\Condition;

class LateCommitConditionTest extends \PHPUnit_Framework_TestCase
{
	public function testConditionIsMetWhenDateAfterEarlyTime()
	{
		$date 		= new \DateTime( '03/08/1984 ' . \CodingPride\Condition\LateCommitCondition::LATE_TIME );
		$limit_date = $date->modify( '+1 minute' );
		$commit 	= $this->getMock( '\CodingPride\Document\Commit', array( 'getDate' ) );
		$commit->expects( $this->once() )->method( 'getDate' )->will( $this->returnValue( $limit_date ) );

		$condition 	= new \CodingPride\Condition\LateCommitCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when the date is correct' );
	}

	public function testConditionIsNotMetWhenDateBeforeEarlyTime()
	{
		$date 		= new \DateTime( '03/08/1984 ' . \CodingPride\Condition\LateCommitCondition::LATE_TIME );
		$limit_date = $date->modify( '-1 minute' );
		$commit 	= $this->getMock( '\CodingPride\Document\Commit', array( 'getDate' ) );
		$commit->expects( $this->once() )->method( 'getDate' )->will( $this->returnValue( $limit_date ) );

		$condition 	= new \CodingPride\Condition\LateCommitCondition();
		$this->assertFalse( $condition->check( $commit ), 'The condition has NOT to be met when the time is too late' );
	}
}
<?php
namespace CodingPride\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';

class BadgeGiverTest extends \PHPUnit_Framework_TestCase
{
	public function testBadgesAreNotGivenToUserWhenConditionAreNotMet()
	{
		$commit 		= $this->getMock( '\CodingPride\Document\Commit' );
		$commit->expects( $this->never() )
			   ->method( 'getAuthor' );
		$badge 			= $this->getMock( '\CodingPride\Document\Badge', array( 'check' ) );
		$badge->expects( $this->once() )
			  ->method( 'check' )
			  ->will( $this->returnValue( false ) );
		$badges 		= new \ArrayIterator( array( $badge ) );
		$badge_giver 	= new \CodingPride\BadgeGiver();
		$badge_giver->giveBadges( new \ArrayIterator( array( $commit ) ), $badges );
	}

	public function testBadgesAreGivenWhenConditionsAreMet()
	{
		$user 			= $this->getMock( '\CodingPride\Document\User', array( 'addBadge' ), array(), '', false );
		$user->expects( $this->once() )
			 ->method( 'addBadge' );
		$commit 		= $this->getMock( '\CodingPride\Document\Commit', array( 'getAuthor' ) );
		$commit->expects( $this->once() )
			  ->method( 'getAuthor' )
			  ->will( $this->returnValue( $user ) );
		$badge 			= $this->getMock( '\CodingPride\Document\Badge', array( 'check' ) );
		$badge->expects( $this->once() )
			  ->method( 'check' )
			  ->will( $this->returnValue( true ) );
		$badges 		= new \ArrayIterator( array( $badge ) );
		$badge_giver 	= new \CodingPride\BadgeGiver();
		$badge_giver->giveBadges( new \ArrayIterator( array( $commit ) ), $badges );
	}
}

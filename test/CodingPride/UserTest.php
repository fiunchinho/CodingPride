<?php
require __DIR__ . '/../../vendor/autoload.php';

class UserTest extends PHPUnit_Framework_TestCase
{
	public function testBadgesAreNotFilteredIfUserHasNoneOfThem()
	{
		$badge 			= $this->getMock( '\CodingPride\Document\Badge' );
		$badge2			= $this->getMock( '\CodingPride\Document\Badge' );
		$badge3			= $this->getMock( '\CodingPride\Document\Badge' );

		$user_badge		= $this->getMock( '\CodingPride\Document\Badge', array( 'getName' ) );
		$user_badge->expects( $this->once() )
			  ->method( 'getName' )
			  ->will( $this->returnValue( 'UserBadge' ) );

		$user 			= new \CodingPride\Document\User();
		$user->addBadge( $user_badge );
		$potential_badges = array( 'FakeBadge' => $badge, 'FakeBadge2' => $badge2, 'FakeBadge3' => $badge3 );
		$badges_to_earn = $user->getUserBadgesToAchieve( $potential_badges );
		$this->assertEquals( count( $badges_to_earn ), 3 );
	}

	public function testOneBadgeFromUserIsFilteredFromPotentialBadges()
	{
		$badge 			= $this->getMock( '\CodingPride\Document\Badge' );
		$badge2			= $this->getMock( '\CodingPride\Document\Badge' );
		$badge3			= $this->getMock( '\CodingPride\Document\Badge' );

		$user_badge		= $this->getMock( '\CodingPride\Document\Badge', array( 'getName' ) );
		$user_badge->expects( $this->once() )
			  ->method( 'getName' )
			  ->will( $this->returnValue( 'UserBadge' ) );

		$user 			= new \CodingPride\Document\User();
		$user->addBadge( $user_badge );
		$potential_badges = array( 'FakeBadge' => $badge, 'FakeBadge2' => $badge2, 'UserBadge' => $badge3 );
		$badges_to_earn = $user->getUserBadgesToAchieve( $potential_badges );
		$this->assertEquals( count( $badges_to_earn ), 2 );
		$this->assertArrayHasKey( 'FakeBadge', $badges_to_earn, 'The badge FakeBadge was not selected.' );
		$this->assertArrayHasKey( 'FakeBadge2', $badges_to_earn, 'The badge FakeBadge2 was not selected.' );
	}

	public function testTwoBadgesAreFilteredFromPotentialBadgesBecauseUserAlreadyEarnedThem()
	{
		$badge 			= $this->getMock( '\CodingPride\Document\Badge' );
		$badge2			= $this->getMock( '\CodingPride\Document\Badge' );
		$badge3			= $this->getMock( '\CodingPride\Document\Badge' );

		$user_badge		= $this->getMock( '\CodingPride\Document\Badge', array( 'getName' ) );
		$user_badge->expects( $this->once() )
			  ->method( 'getName' )
			  ->will( $this->returnValue( 'UserBadge' ) );

		$user 			= new \CodingPride\Document\User();
		$user->addBadge( $user_badge );
		$potential_badges = array( 'UserBadge' => $badge, 'FakeBadge2' => $badge2, 'UserBadge' => $badge3 );
		$badges_to_earn = $user->getUserBadgesToAchieve( $potential_badges );
		$this->assertEquals( count( $badges_to_earn ), 1, 'The number of potential badges is not correct.' );
		$this->assertArrayHasKey( 'FakeBadge2', $badges_to_earn, 'The selected badge is not FakeBadge2.' );
	}

	public function testGettersAndSetters()
	{
		$user = new \CodingPride\Document\User( $name = 'FakeUser' );
		$this->assertEquals( $user->getUsername(), $name, 'The name getter/setter does not work.' );
		$user->setUsername( $name2 = 'FakeUser2' );
		$this->assertEquals( $user->getUsername(), $name2, 'The name getter/setter does not work.' );
	}
}

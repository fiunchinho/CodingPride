<?php
namespace CodingPride\Tests\Document;

class BadgeTest extends \PHPUnit_Framework_TestCase
{
	public function testBadgeIsNotGivenWhenOneConditionIsNotMet()
	{
		$commit 		= $this->getMock( '\CodingPride\Document\Commit' );

		$condition_met 	= $this->getMock( '\CodingPride\Condition', array( 'check' ) );
		$condition_met->expects( $this->once() )
			  ->method( 'check' )
			  ->will( $this->returnValue( true ) );

		$condition_not_met 	= $this->getMock( '\CodingPride\Condition', array( 'check' ) );
		$condition_not_met->expects( $this->once() )
			  ->method( 'check' )
			  ->will( $this->returnValue( false ) );

		$badge 	= new \CodingPride\Document\Badge();
		$badge->setConditions( array( $condition_met, $condition_not_met ) );
		$this->assertFalse( $badge->check( $commit ), 'Conditions are not met but the badge has been given.' );
	}

	
	public function testBadgeIsGivenWhenThereAreNoConditions()
	{
		$commit 	= $this->getMock( '\CodingPride\Document\Commit' );
		$badge 	= new \CodingPride\Document\Badge();
		$this->assertTrue( $badge->check( $commit ), 'Badge must be given when there are no conditions.' );
	}

	public function testBadgeIsGivenWhenAllConditionsAreMet()
	{
		$commit 		= $this->getMock( '\CodingPride\Document\Commit' );

		$condition_met 	= $this->getMock( '\CodingPride\Condition', array( 'check' ) );
		$condition_met->expects( $this->once() )
			  ->method( 'check' )
			  ->will( $this->returnValue( true ) );

		$condition_met2 	= $this->getMock( '\CodingPride\Condition', array( 'check' ) );
		$condition_met2->expects( $this->once() )
			  ->method( 'check' )
			  ->will( $this->returnValue( true ) );

		$badge 	= new \CodingPride\Document\Badge();
		$badge->setConditions( array( $condition_met, $condition_met2 ) );
		$this->assertTrue( $badge->check( $commit ), 'Badge must be given when all conditions are met.' );
	}

	public function testGettersAndSetters()
	{
		$badge 	= new \CodingPride\Document\Badge();
		$badge->setDescription( $description = 'This is a description' );
		$badge->setName( $name = 'This is a name' );
		$badge->setLast_pagination_param( 23 );
		$badge->setActive( 1 );

		$this->assertEquals( $description, $badge->getDescription(), 'The description getter/setter does not work.' );
		$this->assertEquals( $name, $badge->getName(), 'The name getter/setter does not work.' );
		$this->assertEquals( 23, $badge->getLast_pagination_param(), 'The last date checked getter/setter does not work.' );
		$this->assertEquals( null, $badge->getId(), 'The getId method does not work.' );
		$this->assertEquals( (string) $badge, $badge->getName(), 'The toString method does not work.' );
		$this->assertEquals( 1, $badge->isActive(), 'The active setter/getter does not work.' );
	}
	
}

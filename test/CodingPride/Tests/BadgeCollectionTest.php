<?php
namespace CodingPride\Tests;

class BadgeCollectionTest extends \PHPUnit_Framework_TestCase
{
	public function testBadgesAreActivated()
	{
		$badge1	= $this->getMock( '\CodingPride\Document\Badge', array( 'setActive' ) );
		$badge1
			->expects( $this->once() )
			->method( 'setActive' )
			->with( 1 );

		$badge2	= $this->getMock( '\CodingPride\Document\Badge', array( 'setActive' ) );
		$badge2
			->expects( $this->once() )
			->method( 'setActive' )
			->with( 1 );
		$badge_collection = new \CodingPride\BadgeCollection( array( $badge1, $badge2 ) );
		$badge_collection->activateAll();
	}
}
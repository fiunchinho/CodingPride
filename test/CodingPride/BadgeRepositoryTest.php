<?php
namespace CodingPride\Tests;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/OdmTestCase.php';

class BadgeRepositoryTest extends \CodingPride\Tests\OdmTestCase
{
	public function testCreateBadgeWhenItAlreadyExists()
	{
		$badge = $this->getMock( '\CodingPride\Document\Badge', array( 'setConditions' ) );
		$badge->expects( $this->once() )->method( 'setConditions' );

		$badge_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Badge' );

		$badge_repository         = $this->getMock( get_class( $badge_repository ), array( 'findOneByName' ), array(), '', false );
		$badge_repository->expects( $this->once() )
						->method( 'findOneByName' )
						->with( 'badge_name' )
						->will( $this->returnValue( $badge ) );

		$created_badge = $badge_repository->create( 'badge_name', array(), 'text' );
		$this->assertInstanceOf( '\CodingPride\Document\Badge', $created_badge, 'The badge was not created' );
		$this->assertEquals( $badge, $created_badge, 'The badge was not created right' );
	}

	public function testCreateBadgeWhenItDoesNotExist()
	{
		$this->markTestSkipped();
		$badge_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Badge' );

		$badge_repository         = $this->getMock( get_class( $badge_repository ), array( 'findOneByName' ), array(), '', false );
		$badge_repository->expects( $this->once() )
						->method( 'findOneByName' )
						->with( 'badge_name' )
						->will( $this->returnValue( false ) );

		$created_badge = $badge_repository->create( 'badge_name', array(), 'text' );
		$this->assertInstanceOf( '\CodingPride\Document\Badge', $created_badge, 'The badge was not created' );
		$this->assertEquals( $badge, $created_badge, 'The badge was not created right' );
	}
}
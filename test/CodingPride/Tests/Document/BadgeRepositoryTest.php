<?php
namespace CodingPride\Tests\Document;

class BadgeRepositoryTest extends OdmTestCase
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
		$this->assertSame( $badge, $created_badge, 'The badge was not created right' );
	}
	
	public function testCreateBadgeWhenItDoesNotExist()
	{
		$expected_badge_name = 'badge_name';
		$expected_badge_description = 'text';
		$expected_badge_conditions = array();

		$badge_repository = $this->_getTestDocumentManager()->getRepository( 'CodingPride\Document\Badge' );

		$created_badge = $badge_repository->create( $expected_badge_name, $expected_badge_conditions, $expected_badge_description );
		
		$this->assertInstanceOf( '\CodingPride\Document\Badge', $created_badge, 'The badge was not created' );
		$this->assertEquals( $expected_badge_name, $created_badge->getName(), 'The badge name was not set' );
		$this->assertEquals( $expected_badge_description, $created_badge->getDescription(), 'The badge description was not set' );
		$this->assertEquals( $expected_badge_conditions, $created_badge->getConditions(), 'The badge description was not set' );
	}
}
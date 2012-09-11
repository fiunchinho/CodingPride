<?php
namespace CodingPride\Tests;

class BadgeFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function testBadgesAreCreatedAndTheyAreActive()
	{
		$badges_from_config = array(
			'Badge1' => array(
				'name' 			=> 'Badge1',
				'conditions' 	=> array( 'condition' ),
				'description' 	=> 'badge description'
			),
			'Badge2' => array(
				'name' 			=> 'Badge2',
				'conditions' 	=> array( 'condition2' ),
				'description' 	=> 'badge2 description'
			)
		);

		$badge 	= $this->getMock( '\CodingPride\Document\Badge', array( 'isActive' ) );
		$badge->expects( $this->exactly( 2 ) )->method( 'isActive' )->will( $this->returnValue( true ) );

		$badge_repository 	= $this->getMock( '\CodingPride\Document\BadgeRepository', array( 'create' ), array(), '', false );
		$badge_repository->expects( $this->exactly( 2 ) )->method( 'create' )->will( $this->returnValue( $badge ) );

		$dm 				= $this->getMock( '\CodingPride\Tests\DocumentManager', array( 'getRepository', 'flush' ), array(), '', false );
		$dm->expects( $this->exactly( 2 ) )->method( 'getRepository' )->will( $this->returnValue( $badge_repository ) );
		
		$factory 			= $this->getMock( '\CodingPride\BadgeFactory', array( 'createConditions' ), array( $dm, $badges_from_config ) );
		$factory->expects( $this->exactly( 2 ) )->method( 'createConditions' )->will( $this->returnValue( true ) );

		$badges 			= $factory->getBadges();
		$this->assertEquals( 2, count( $badges ), 'The number of created badges is not right.' );
	}

	public function testBadgesAreCreatedAndWellSeparated()
	{
		$badges_from_config = array(
			'Badge1' => array(
				'name' 			=> 'Badge1',
				'conditions' 	=> array( 'condition' ),
				'description' 	=> 'badge description'
			),
			'Badge2' => array(
				'name' 			=> 'Badge2',
				'conditions' 	=> array( 'condition2' ),
				'description' 	=> 'badge2 description'
			)
		);

		$badge 	= $this->getMock( '\CodingPride\Document\Badge', array( 'isActive' ) );
		$badge->expects( $this->at( 0 ) )->method( 'isActive' )->will( $this->returnValue( true ) );
		$badge->expects( $this->at( 1 ) )->method( 'isActive' )->will( $this->returnValue( false ) );

		$badge_repository 	= $this->getMock( '\CodingPride\Document\BadgeRepository', array( 'create' ), array(), '', false );
		$badge_repository->expects( $this->exactly( 2 ) )->method( 'create' )->will( $this->returnValue( $badge ) );

		$dm 				= $this->getMock( '\CodingPride\Tests\DocumentManager', array( 'getRepository', 'flush' ), array(), '', false );
		$dm->expects( $this->exactly( 2 ) )->method( 'getRepository' )->will( $this->returnValue( $badge_repository ) );
		
		$factory 			= $this->getMock( '\CodingPride\BadgeFactory', array( 'createConditions' ), array( $dm, $badges_from_config ) );
		$factory->expects( $this->exactly( 2 ) )->method( 'createConditions' )->will( $this->returnValue( true ) );

		$inactive_badges	= $factory->getInactiveBadges();
		$badges 			= $factory->getBadges();

		$this->assertEquals( 1, count( $badges ), 'The number of created badges is not right.' );
		$this->assertEquals( 1, count( $inactive_badges ), 'The number of created badges is not right.' );
	}

	/**
     * Creates an DocumentManager for testing purposes.
     *
     * @return Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function _getTestDocumentManager()
    {
        \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::registerAnnotationClasses();

        $config = new \Doctrine\ODM\MongoDB\Configuration();
        $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());

        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('Proxies');

        $config->setHydratorDir(__DIR__ . '/Hydrators');
        $config->setHydratorNamespace('Hydrators');

        return \CodingPride\Tests\DocumentManagerMock::create( new \CodingPride\Tests\ConnectionMock(), $config );
    }
}
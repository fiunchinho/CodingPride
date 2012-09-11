<?php
namespace CodingPride\Tests\Repository;

class GitLocalTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLatestCommitsWhenAllOfThemAreNew()
	{
        $config = array( 'repository'    => 'repo' );

		$repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository
            ->expects( $this->exactly( 3 ) )
            ->method( 'create' )
            ->will( $this->returnValue( true ) );

		$database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database
            ->expects( $this->once() )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );


		$api = $this->getMock( '\CodingPride\Repository\GitLocal', array( 'getCommitsFromConsole' ), array( $database, $config ) );
        $api
        	->expects( $this->exactly( 1 ) )
        	->method( 'getCommitsFromConsole' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getCommitListFromConsole' ) ) );

		$commits_collection = $api->getCommits();
		$this->assertEquals( 3, count( $commits_collection ), 'The number of commits parsed from console is wrong.' );
	}

    public function testGetLatestCommitsWhenOneOfThemIsNew()
    {
        $config = array( 'repository'    => 'repo' );

        $repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
        $repository
            ->expects( $this->exactly( 3 ) )
            ->method( 'create' )
            ->will( $this->onConsecutiveCalls( false, false, true ) );

        $database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
        $database
            ->expects( $this->once() )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );


        $api = $this->getMock( '\CodingPride\Repository\GitLocal', array( 'getCommitsFromConsole' ), array( $database, $config ) );
        $api
            ->expects( $this->exactly( 1 ) )
            ->method( 'getCommitsFromConsole' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getCommitListFromConsole' ) ) );

        $commits_collection = $api->getCommits();
        $this->assertEquals( 1, count( $commits_collection ), 'The number of commits parsed from console is wrong.' );
    }

    public static function getCommitListFromConsole()
    {
        return <<<OUTPUT
{-start-} fa40bd0143fc2bbfab87c2d691ee3fe010b5922d || fiunchinho || Thu Aug 30 23:46:35 2012 +0200 || Doing travis pass\ ||
lib/CodingPride/cron2.php
phpunit.xml.dist

{-start-} 835b6fcfd81dab36d32baac8df1ed8240300e5d4 || fiunchinho || Thu Aug 30 18:37:07 2012 +0200 || Removing old class\ ||
lib/CodingPride/Source/CommitApiWrapperAbstract.php

{-start-} efc187cf8529ac1026b39f3595c837949c812495 || Jose Armesto || Thu Aug 30 15:46:05 2012 +0300 || Fixing phpunit command for travis config file\ ||
.travis.yml
OUTPUT;
    }
}
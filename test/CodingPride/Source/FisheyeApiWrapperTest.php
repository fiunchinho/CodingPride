<?php
namespace CodingPride\Source\Tests;

require_once __DIR__ . '/../../../vendor/autoload.php';

class FisheyeApiWrapperTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLatestCommitsWhenAllOfThemAreNew()
	{
		$repository         = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository->expects( $this->exactly( 4 ) )->method( 'create' )->will( $this->returnValue( true ) );
		$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );
		
		$http               = $this->getMock( '\CodingPride\Http', array( 'get' ) );
        $http->expects( $this->at( 0 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $http->expects( $this->exactly( 5 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $config 			= array( 'login' => 'login', 'password' => 'pass', 'username' => 'user', 'repository' => 'repo', 'host' => 'fisheye' );
        $api                = new \CodingPride\Source\FisheyeApiWrapper( $database, $config, $http );
		$commits_collection = $api->getLatestCommits();
		$this->assertEquals( 4, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}


	public function testGetLatestCommitsWhenOneOfThemIsNew()
	{
		$repository         = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository->expects( $this->exactly( 4 ) )->method( 'create' )->will ( $this->onConsecutiveCalls( true, true, false, false ) );
		
		$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );
		
		$http               = $this->getMock( '\CodingPride\Http', array( 'get' ) );
        $http->expects( $this->at( 0 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $http->expects( $this->exactly( 5 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $config 			= array( 'login' => 'login', 'password' => 'pass', 'username' => 'user', 'repository' => 'repo', 'host' => 'fisheye' );
        $api                = new \CodingPride\Source\FisheyeApiWrapper( $database, $config, $http );
		$commits_collection = $api->getLatestCommits();
		$this->assertEquals( 2, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}

	public static function getJsonCommitDetails()
	{
		return file_get_contents( __DIR__ . '/fisheye_commit_details.json' );
	}

	public static function getJsonResponseFromApi()
	{
		return file_get_contents( __DIR__ . '/fisheye_latest_commits.json' );
	}
	
}
<?php
namespace CodingPride\Source\Tests;

require_once __DIR__ . '/../../../vendor/autoload.php';

class GithubApiWrapperTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLatestCommitsWhenAllOfThemAreNew()
	{
		$repository         = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository->expects( $this->exactly( 2 ) )->method( 'create' )->will( $this->returnValue( true ) );
		$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );
		
		$http               = $this->getMock( '\CodingPride\Http', array( 'get' ) );
        $http->expects( $this->at( 0 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $http->expects( $this->exactly( 3 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $config 			= array( 'username' => 'user', 'repository' => 'repo' );
        $api                = new \CodingPride\Source\GithubApiWrapper( $database, $config, $http );
		$commits_collection = $api->getLatestCommits();
		$this->assertEquals( 2, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}

	public function testGetLatestCommitsWhenOneOfThemIsNew()
	{
		$repository         = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository->expects( $this->at( 0 ) )->method( 'create' )->will( $this->returnValue( true ) );
		$repository->expects( $this->at( 1 ) )->method( 'create' )->will( $this->returnValue( false ) );
		$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );
		
		$http               = $this->getMock( '\CodingPride\Http', array( 'get' ) );
        $http->expects( $this->at( 0 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $http->expects( $this->exactly( 3 ) )->method( 'get' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $config 			= array( 'username' => 'user', 'repository' => 'repo' );
        $api                = new \CodingPride\Source\GithubApiWrapper( $database, $config, $http );
		$commits_collection = $api->getLatestCommits();
		$this->assertEquals( 1, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}

	public function getJsonCommitDetails()
	{
		return file_get_contents( __DIR__ . '/github_commit_details.json' );
	}

	public function getJsonResponseFromApi()
	{
		return file_get_contents( __DIR__ . '/github_latest_commits.json' );
	}
}
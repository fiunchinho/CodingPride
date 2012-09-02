<?php
namespace CodingPride\Source\Tests;

class AbstractApiWrapperTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLatestCommitsWhenAllOfThemAreNew()
	{
		$repository         = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository->expects( $this->exactly( 2 ) )->method( 'create' )->will( $this->returnValue( true ) );

		$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );

		$response           = $this->getMock( '\Guzzle\Http\Message\Response', array( 'getBody' ), array( 200 ) );
        $response->expects( $this->at( 0 ) )->method( 'getBody' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $response->expects( $this->exactly( 3 ) )->method( 'getBody' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $request = $this->getMock( '\Guzzle\Http\Message\Request', array( 'send' ), array( 'get', 'foo' ) );
        $request
        	->expects( $this->at( 0 ) )
        	->method( 'send' )
        	->will( $this->returnValue( $response ) );
        $request
        	->expects( $this->exactly( 3 ) )
        	->method( 'send' )
        	->will( $this->returnValue( $response ) );

		$http = $this->getMock( '\Guzzle\Http\Client', array( 'get' ) );
        $http
        	->expects( $this->at( 0 ) )
        	->method( 'get' )
        	->with( $this->stringContains( 'query=string', true ) )
        	->will( $this->returnValue( $request ) );
        $http
        	->expects( $this->exactly( 3 ) )
        	->method( 'get' )
        	->will( $this->returnValue( $request ) );

        $config = array(
        	'host'			=> 'host',
        	'login' 		=> 'login',
        	'password' 		=> 'pass',
        	'username' 		=> 'user',
        	'repository' 	=> 'repo'
        );
        $api_name			= $this->getWrapperName();
        $api                = new $api_name( $database, $config, $http );
		$commits_collection = $api->getLatestCommits( array( 'query' => 'string' ) );
		$this->assertEquals( 2, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}

	
	public function testGetLatestCommitsWhenOneOfThemIsNew()
	{
		$repository         = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository->expects( $this->at( 0 ) )->method( 'create' )->will( $this->returnValue( true ) );
		$repository->expects( $this->at( 1 ) )->method( 'create' )->will( $this->returnValue( false ) );
		$database 	        = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $repository ) );
		
		$response           = $this->getMock( '\Guzzle\Http\Message\Response', array( 'getBody' ), array( 200 ) );
        $response->expects( $this->at( 0 ) )->method( 'getBody' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $response->expects( $this->exactly( 3 ) )->method( 'getBody' )->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $request            = $this->getMock( '\Guzzle\Http\Message\Request', array( 'send' ), array( 'get', 'foo' ) );
        $request->expects( $this->at( 0 ) )->method( 'send' )->will( $this->returnValue( $response ) );
        $request->expects( $this->exactly( 3 ) )->method( 'send' )->will( $this->returnValue( $response ) );

		$http               = $this->getMock( '\Guzzle\Http\Client', array( 'get' ) );
        $http->expects( $this->at( 0 ) )->method( 'get' )->will( $this->returnValue( $request ) );
        $http->expects( $this->exactly( 3 ) )->method( 'get' )->will( $this->returnValue( $request ) );

        $config = array(
        	'host'			=> 'host',
        	'login' 		=> 'login',
        	'password' 		=> 'pass',
        	'username' 		=> 'user',
        	'repository' 	=> 'repo'
        );
        $api_name			= $this->getWrapperName();
        $api                = new $api_name( $database, $config, $http );
		$commits_collection = $api->getLatestCommits();
		$this->assertEquals( 1, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}
}
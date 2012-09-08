<?php
namespace CodingPride\Tests\Repository;

class AbstractApiTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLatestCommitsWhenAllOfThemAreNew()
	{
        $config = array(
            'host'          => 'host',
            'login'         => 'login',
            'password'      => 'pass',
            'username'      => 'user',
            'repository'    => 'repo'
        );

		$repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository
            ->expects( $this->exactly( 2 ) )
            ->method( 'create' )
            ->will( $this->returnValue( true ) );

		$database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database
            ->expects( $this->exactly( 2 ) )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );

		$response = $this->getMock( '\Guzzle\Http\Message\Response', array( 'getBody' ), array( 200 ) );
        $response
            ->expects( $this->at( 0 ) )
            ->method( 'getBody' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $response
            ->expects( $this->at( 3 ) )
            ->method( 'getBody' )
            ->will( $this->returnValue( '' ) );
        $response
            ->expects( $this->exactly( 4 ) )
            ->method( 'getBody' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $request = $this->getMock( '\Guzzle\Http\Message\Request', array( 'send', 'setAuth' ), array( 'GET', '' ) );
        $request
        	->expects( $this->exactly( 4 ) )
        	->method( 'send' )
        	->will( $this->returnValue( $response ) );

		$http = $this->getMock( '\Guzzle\Http\Client', array( 'get' ) );            
        $http
        	->expects( $this->exactly( 4 ) )
        	->method( 'get' )
        	->will( $this->returnValue( $request ) );

        $api_name			= $this->getWrapperName();
        $api                = new $api_name( $database, $config, $http );
		$commits_collection = $api->getCommits();
		$this->assertEquals( 2, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}

	
	public function testGetLatestCommitsWhenOneOfThemIsNew()
	{
		$config = array(
            'host'          => 'host',
            'login'         => 'login',
            'password'      => 'pass',
            'username'      => 'user',
            'repository'    => 'repo'
        );

        $repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
        $repository
            ->expects( $this->at( 0 ) )
            ->method( 'create' )
            ->will( $this->returnValue( true ) );
        $repository
            ->expects( $this->at( 1 ) )
            ->method( 'create' )
            ->will( $this->returnValue( false ) );

        $database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
        $database
            ->expects( $this->exactly( 2 ) )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );

        $response = $this->getMock( '\Guzzle\Http\Message\Response', array( 'getBody' ), array( 200 ) );
        $response
            ->expects( $this->at( 0 ) )
            ->method( 'getBody' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $response
            ->expects( $this->at( 3 ) )
            ->method( 'getBody' )
            ->will( $this->returnValue( '' ) );
        $response
            ->expects( $this->exactly( 4 ) )
            ->method( 'getBody' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $request = $this->getMock( '\Guzzle\Http\Message\Request', array( 'send', 'setAuth' ), array( 'GET', '' ) );
        $request
            ->expects( $this->exactly( 4 ) )
            ->method( 'send' )
            ->will( $this->returnValue( $response ) );

        $http = $this->getMock( '\Guzzle\Http\Client', array( 'get' ) );            
        $http
            ->expects( $this->exactly( 4 ) )
            ->method( 'get' )
            ->will( $this->returnValue( $request ) );

        $api_name			= $this->getWrapperName();
        $api                = new $api_name( $database, $config, $http );
		$commits_collection = $api->getCommits();
		$this->assertEquals( 1, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
	}

    public function testGetLatestCommitsReachingApiLimit()
    {
        $config = array(
            'host'          => 'host',
            'login'         => 'login',
            'password'      => 'pass',
            'username'      => 'user',
            'repository'    => 'repo'
        );

        $repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
        $repository
            ->expects( $this->at( 0 ) )
            ->method( 'create' )
            ->will( $this->returnValue( true ) );
        $repository
            ->expects( $this->at( 1 ) )
            ->method( 'create' )
            ->will( $this->returnValue( false ) );

        $database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
        $database
            ->expects( $this->exactly( 1 ) )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );

        $response = $this->getMock( '\Guzzle\Http\Message\Response', array( 'getBody' ), array( 200 ) );
        $response
            ->expects( $this->at( 0 ) )
            ->method( 'getBody' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getJsonResponseFromApi' ) ) );
        $response
            ->expects( $this->exactly( 3 ) )
            ->method( 'getBody' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getJsonCommitDetails' ) ) );

        $request = $this->getMock( '\Guzzle\Http\Message\Request', array( 'send', 'setAuth' ), array( 'GET', '' ) );
        $request
            ->expects( $this->exactly( 3 ) )
            ->method( 'send' )
            ->will( $this->returnValue( $response ) );

        $http = $this->getMock( '\Guzzle\Http\Client', array( 'get' ) );            
        $http
            ->expects( $this->exactly( 3 ) )
            ->method( 'get' )
            ->will( $this->returnValue( $request ) );

        $this->setExpectedException( '\CodingPride\Repository\ApiUsageLimitReachedException' );

        $api_name           = $this->getWrapperNameWithLowApiUsageLimit();
        $api                = new $api_name( $database, $config, $http );
        $commits_collection = $api->getCommits();
        $this->assertEquals( 1, count( $commits_collection ), 'The number of commits parsed from json is wrong.' );
    }
}
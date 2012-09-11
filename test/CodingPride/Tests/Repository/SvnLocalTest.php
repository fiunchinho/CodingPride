<?php
namespace CodingPride\Tests\Repository;

class SvnLocalTest extends \PHPUnit_Framework_TestCase
{
	public function testGetLatestCommitsWhenAllOfThemAreNew()
	{
        $config = array( 'repository'    => 'repo' );

		$repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
		$repository
            ->expects( $this->exactly( 2 ) )
            ->method( 'create' )
            ->will( $this->returnValue( true ) );

		$database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
		$database
            ->expects( $this->once() )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );


		$svn_api = $this->getMock( '\CodingPride\Repository\SvnLocal', array( 'getCommitsFromConsole' ), array( $database, $config ) );
        $svn_api
        	->expects( $this->exactly( 1 ) )
        	->method( 'getCommitsFromConsole' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getCommitListFromConsole' ) ) );

		$commits_collection = $svn_api->getCommits();
		$this->assertEquals( 2, count( $commits_collection ), 'The number of commits parsed from console is wrong.' );
	}

    public function testGetLatestCommitsWhenOneOfThemIsNew()
    {
        $config = array( 'repository'    => 'repo' );

        $repository = $this->getMock( '\CodingPride\CommitRepository', array( 'create' ), array(), '', false );
        $repository
            ->expects( $this->exactly( 2 ) )
            ->method( 'create' )
            ->will( $this->onConsecutiveCalls( true, false ) );

        $database = $this->getMock( '\Doctrine\ODM\MongoDB\DocumentManager', array( 'getRepository' ), array(), '', false );
        $database
            ->expects( $this->once() )
            ->method( 'getRepository' )
            ->will( $this->returnValue( $repository ) );


        $svn_api = $this->getMock( '\CodingPride\Repository\SvnLocal', array( 'getCommitsFromConsole' ), array( $database, $config ) );
        $svn_api
            ->expects( $this->exactly( 1 ) )
            ->method( 'getCommitsFromConsole' )
            ->will( $this->returnCallback( array( get_class( $this ), 'getCommitListFromConsole' ) ) );

        $commits_collection = $svn_api->getCommits();
        $this->assertEquals( 1, count( $commits_collection ), 'The number of commits parsed from console is wrong.' );
    }

    public static function getCommitListFromConsole()
    {
        return array(
            array(
                'rev'   => 1,
                'author'    => 'john',
                'msg'       => 'Test commit svn',
                'date'      => '2012-06-05T20:57:02.887170Z',
                'paths'     => array(
                    array(
                        'action'    => 'M',
                        'path'      => '/var/index.php'
                    ),
                    array(
                        'action'    => 'A',
                        'path'      => '/var/js.js'
                    )
                )
            ),
            array(
                'rev'   => 2,
                'author'    => 'mike',
                'msg'       => 'Another commit',
                'date'      => '2012-07-05T20:57:02.887170Z',
                'paths'     => array(
                    array(
                        'action'    => 'M',
                        'path'      => '/var/index.php'
                    ),
                    array(
                        'action'    => 'A',
                        'path'      => '/var/js.js'
                    )
                )
            )
        );
    }
}
<?php
namespace CodingPride\Tests;

class CommitTest extends \PHPUnit_Framework_TestCase
{
	public function testGettersAndSetters()
	{
		$commit 	= new \CodingPride\Document\Commit();
		$commit->setIn_game( 1 );
		$commit->setBranch( $branch = 'Branch' );
		$commit->setRevision( $revision = 123 );
		$commit->setParent( $parent = 123 );
		$commit->setChild( $child = 123 );
		$commit->setComment( $comment = 'This is a comment' );
		$commit->setAuthor( $user = $this->getMock( '\CodingPride\Document\User' ) );
		$commit->setDate( $date = new \DateTime( '03/08/1984' ) );
		$commit->setFiles( $files = array( 'index.php', 'header.php' ) );


		$this->assertEquals( 1, $commit->getIn_game(), 'The in_game getter/setter does not work.' );
		$this->assertEquals( $branch, $commit->getBranch(), 'The branch getter/setter does not work.' );
		$this->assertEquals( $revision, $commit->getRevision(), 'The revision getter/setter does not work.' );
		$this->assertEquals( $comment, $commit->getComment(), 'The comment getter/setter does not work.' );
		$this->assertEquals( $child, $commit->getChild(), 'The child getter/setter does not work.' );
		$this->assertEquals( $parent, $commit->getParent(), 'The parent getter/setter does not work.' );
		$this->assertEquals( $user, $commit->getAuthor(), 'The author getter/setter does not work.' );
		$this->assertEquals( $files, $commit->getFiles(), 'The files getter/setter does not work.' );
		$this->assertEquals( $date, $commit->getDate(), 'The date getter/setter does not work.' );
	}

	public function testThatFilesAreCorrectlySetted()
	{
		$commit 	= new \CodingPride\Document\Commit();
		$commit->setFiles( array( array( 'path' => 'index.php', 'info' => null ), array( 'path' => 'javascript.js', 'info' => null ) ) );
		$commit->postLoad();
		$files = $commit->getFiles();

		$this->assertEquals( 2, count( $files ), 'The number of files is not correct.' );
		$this->assertInstanceOf( '\CodingPride\File', $files[0], 'Must be a File instance.' );
	}
}

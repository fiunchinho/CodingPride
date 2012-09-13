<?php
namespace CodingPride\Tests\Condition;

class ChangeOldFileConditionTest extends \PHPUnit_Framework_TestCase
{

	public function testConditionIsMetWhenLastCommitWasFarIntoThePast()
	{
		$date 		= new \DateTime( '03/08/2012' );
		$far_date 	= new \DateTime( '03/08/1984' );

		$file = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( 'file_path.php' ) );
		$files = array( $file );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles', 'getDate' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( $files ) );
		$commit->expects( $this->once() )->method( 'getDate' )->will( $this->returnValue( $date ) );

		$commit_to_check_against = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles', 'getDate' ) );
		$commit_to_check_against->expects( $this->once() )->method( 'getDate' )->will( $this->returnValue( $far_date ) );

		$cursor = $this->getMock( 'Doctrine\MongoDB\Cursor', array( 'hasNext', 'getNext' ), array(), '', false );
		$cursor->expects( $this->once() )->method( 'hasNext' )->will( $this->returnValue( true ) );
		$cursor->expects( $this->once() )->method( 'getNext' )->will( $this->returnValue( $commit_to_check_against ) );

		$commit_repository 	= $this->getMock( '\CodingPride\Document\CommitRepository', array( 'findBy' ), array(), '', false );
		$commit_repository->expects( $this->once() )->method( 'findBy' )->will( $this->returnValue( $cursor ) );

		$dm = $this->getMock( '\CodingPride\Tests\DocumentManager', array( 'getRepository' ), array(), '', false );
		$dm->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $commit_repository ) );
		
		$condition 		= new \CodingPride\Condition\ChangeOldFileCondition( $dm );

		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when last commit in that file was long ago.' );
	}

	public function testConditionIsNotMetWhenLastCommitWasCloseInTime()
	{
		$date 		= new \DateTime( '1984-08-04' );
		$far_date 	= new \DateTime( '1984-08-03' );

		$file = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( 'file_path.php' ) );
		$files = array( $file );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles', 'getDate' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( $files ) );
		$commit->expects( $this->once() )->method( 'getDate' )->will( $this->returnValue( $date ) );

		$commit_to_check_against = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles', 'getDate' ) );
		$commit_to_check_against->expects( $this->once() )->method( 'getDate' )->will( $this->returnValue( $far_date ) );

		$cursor = $this->getMock( 'Doctrine\MongoDB\Cursor', array( 'hasNext', 'getNext' ), array(), '', false );
		$cursor->expects( $this->once() )->method( 'hasNext' )->will( $this->returnValue( true ) );
		$cursor->expects( $this->once() )->method( 'getNext' )->will( $this->returnValue( $commit_to_check_against ) );

		$commit_repository 	= $this->getMock( '\CodingPride\Document\CommitRepository', array( 'findBy' ), array(), '', false );
		$commit_repository->expects( $this->once() )->method( 'findBy' )->will( $this->returnValue( $cursor ) );

		$dm = $this->getMock( '\CodingPride\Tests\DocumentManager', array( 'getRepository' ), array(), '', false );
		$dm->expects( $this->once() )->method( 'getRepository' )->will( $this->returnValue( $commit_repository ) );
		
		$condition 		= new \CodingPride\Condition\ChangeOldFileCondition( $dm );

		$this->assertFalse( $condition->check( $commit ), 'If the last commit in the same file was a few days ago, the condition must not be met' );
	}
}
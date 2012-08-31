<?php
namespace CodingPride\Tests\Condition;

class LibsContributorConditionTest extends \PHPUnit_Framework_TestCase
{
	public function testConditionIsMetWhenRightPath()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( '/backend/libs/public/index.php' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file ) ) );

		$condition = new \CodingPride\Condition\LibsContributorCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when the path matches.' );
	}

	public function testConditionIsMetWhenTheFirstFileHasRightPath()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( '/backend/libs/public/index.php' ) );
		$file2 = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file2->expects( $this->never() )->method( 'getPath' )->will( $this->returnValue( '/js' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file, $file2 ) ) );

		$condition = new \CodingPride\Condition\LibsContributorCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when one file matches the path.' );
	}

	public function testConditionIsMetWhenTheSecondFileHasRightPath()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( '/backend/libs/public/index.php' ) );
		$file2 = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file2->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( '/js' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file2, $file ) ) );

		$condition = new \CodingPride\Condition\LibsContributorCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when the path match the expectation.' );
	}

	public function testConditionIsNotMetWhenNoFilesHaveRightPath()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( '/asd/' ) );
		$file2 = $this->getMock( '\CodingPride\Document\File', array( 'getPath' ) );
		$file2->expects( $this->once() )->method( 'getPath' )->will( $this->returnValue( '/deff/' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file, $file2 ) ) );

		$condition = new \CodingPride\Condition\LibsContributorCondition();
		$this->assertFalse( $condition->check( $commit ), 'The condition has NOT to be met when the path does not match.' );
	}
}
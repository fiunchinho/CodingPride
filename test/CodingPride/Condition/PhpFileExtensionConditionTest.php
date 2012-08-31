<?php
namespace CodingPride\Tests\Condition;

class PhpFileExtensionConditionTest extends \PHPUnit_Framework_TestCase
{
	public function testConditionIsMetWhenRightExtension()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file->expects( $this->once() )->method( 'getExtension' )->will( $this->returnValue( 'php' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file ) ) );

		$condition = new \CodingPride\Condition\PhpFileExtensionCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when the file is a php file.' );
	}

	public function testConditionIsMetWhenTheFirstFileHasRightExtension()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file->expects( $this->once() )->method( 'getExtension' )->will( $this->returnValue( 'php' ) );
		$file2 = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file2->expects( $this->never() )->method( 'getExtension' )->will( $this->returnValue( 'js' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file, $file2 ) ) );

		$condition = new \CodingPride\Condition\PhpFileExtensionCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when one file is a php file.' );
	}

	public function testConditionIsMetWhenTheSecondFileHasRightExtension()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file->expects( $this->once() )->method( 'getExtension' )->will( $this->returnValue( 'php' ) );
		$file2 = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file2->expects( $this->once() )->method( 'getExtension' )->will( $this->returnValue( 'js' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file2, $file ) ) );

		$condition = new \CodingPride\Condition\PhpFileExtensionCondition();
		$this->assertTrue( $condition->check( $commit ), 'The condition has to be met when one file is a php file.' );
	}

	public function testConditionIsNotMetWhenNoFilesHaveRightExtension()
	{
		$file = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file->expects( $this->once() )->method( 'getExtension' )->will( $this->returnValue( 'css' ) );
		$file2 = $this->getMock( '\CodingPride\Document\File', array( 'getExtension' ) );
		$file2->expects( $this->once() )->method( 'getExtension' )->will( $this->returnValue( 'js' ) );

		$commit = $this->getMock( '\CodingPride\Document\Commit', array( 'getFiles' ) );
		$commit->expects( $this->once() )->method( 'getFiles' )->will( $this->returnValue( array( $file, $file2 ) ) );

		$condition = new \CodingPride\Condition\PhpFileExtensionCondition();
		$this->assertFalse( $condition->check( $commit ), 'The condition has NOT to be met when there are no php files.' );
	}
}
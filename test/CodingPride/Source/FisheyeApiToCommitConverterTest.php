<?php
namespace CodingPride\Source\Tests;

require __DIR__ . '/../../../vendor/autoload.php';

class FisheyeApiToCommitConverterTest extends \PHPUnit_Framework_TestCase
{
	public function testConvertionToCommit()
	{
		$this->markTestSkipped();
		$commit_info = json_decode( file_get_contents( __DIR__ . '/fisheye_commit_details.json' ), true );
		$converter = new \CodingPride\Source\FisheyeApiToCommitConverter();
        $commit = $converter->convert( $commit_info );
        $this->assertEquals( $commit->getAuthorUsername(), 'user.name', 'The commit username is not right.' );
	}

	public function testGetRevisionFromCommitInfo()
	{
		$this->markTestSkipped();
		$commit_info = json_decode( file_get_contents( __DIR__ . '/fisheye_commit_details.json' ), true );
		$converter = new \CodingPride\Source\FisheyeApiToCommitConverter();
		$this->assertEquals( '1', $converter->getRevision( $commit_info ), 'The revision value is not right.' );
	}
}
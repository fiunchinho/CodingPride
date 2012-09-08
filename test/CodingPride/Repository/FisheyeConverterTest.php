<?php
namespace CodingPride\Repository\Tests;

class FisheyeConverterTest extends \PHPUnit_Framework_TestCase
{
	public function testConvertionToCommit()
	{
		$commit_info = file_get_contents( __DIR__ . '/fisheye_commit_details.json' );
		$converter = new \CodingPride\Repository\FisheyeConverter();
        $commit = $converter->convert( $commit_info );
        $this->assertEquals( 'borja.morales', $commit->getAuthorUsername(), 'The commit username is not right.' );
	}

	public function testGetRevisionFromCommitInfo()
	{
		$commit_info 	= json_decode( file_get_contents( __DIR__ . '/fisheye_latest_commits.json' ), true );
		$converter 		= new \CodingPride\Repository\FisheyeConverter();
		$this->assertEquals( '74815', $converter->getRevision( $commit_info['csid'][0] ), 'The revision value is not right.' );
	}
}
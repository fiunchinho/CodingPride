<?php
namespace CodingPride\Source\Tests;

require __DIR__ . '/../../../vendor/autoload.php';

class BitbucketApiToCommitConverterTest extends \PHPUnit_Framework_TestCase
{
	public function testConvertionToCommit()
	{
		$commit_info = json_decode( file_get_contents( __DIR__ . '/bitbucket_commit_details.json' ), true );
		$converter = new \CodingPride\Source\BitbucketApiToCommitConverter();
        $commit = $converter->convert( $commit_info );
        $this->assertEquals( $commit->getAuthorUsername(), 'Jose Armesto', 'The commit username is not right.' );
	}

	public function testGetRevisionFromCommitInfo()
	{
		$commit_info = json_decode( file_get_contents( __DIR__ . '/bitbucket_commit_details.json' ), true );
		$converter = new \CodingPride\Source\BitbucketApiToCommitConverter();
		$this->assertEquals( 'b970637496f76a47c8c93533143c46a6b514c4f4', $converter->getRevision( $commit_info ), 'The revision value is not right.' );
	}
}
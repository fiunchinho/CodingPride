<?php
namespace CodingPride\Tests\Repository;

class GithubConverterTest extends \PHPUnit_Framework_TestCase
{
	public function testConvertionToCommit()
	{
		$commit_info = file_get_contents( __DIR__ . '/github_commit_details.json' );
		$converter = new \CodingPride\Repository\GithubConverter();
        $commit = $converter->convert( $commit_info );
        $this->assertEquals( $commit->getAuthorUsername(), 'magmax', 'The commit username is not right.' );
	}

	public function testGetRevisionFromCommitInfo()
	{
		$commit_info = json_decode( file_get_contents( __DIR__ . '/github_commit_details.json' ), true );
		$converter = new \CodingPride\Repository\GithubConverter();
		$this->assertEquals( 'eaa00262f7f0274bda6652660d5de9d392b4a5cc', $converter->getRevision( $commit_info ), 'The revision value is not right.' );
	}
}
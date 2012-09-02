<?php
namespace CodingPride\Source\Tests;

require_once __DIR__ . '/AbstractApiWrapper.php';

class GithubApiWrapperTest extends AbstractApiWrapperTest
{
	public function getWrapperName()
	{
		return '\CodingPride\Source\GithubApiWrapper';
	}

	public static function getJsonCommitDetails()
	{
		return file_get_contents( __DIR__ . '/github_commit_details.json' );
	}

	public static function getJsonResponseFromApi()
	{
		return file_get_contents( __DIR__ . '/github_latest_commits.json' );
	}
}
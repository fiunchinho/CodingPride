<?php
namespace CodingPride\Tests\Repository;

class GithubApiTest extends AbstractApi
{
	public function getWrapperName()
	{
		return '\CodingPride\Repository\GithubApi';
	}

	public function getWrapperNameWithLowApiUsageLimit()
	{
		return '\CodingPride\Tests\Repository\GithubApiLowLimit';
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
<?php
namespace CodingPride\Tests\Repository;

class BitbucketApiTest extends AbstractApi
{
	public function getWrapperName()
	{
		return '\CodingPride\Repository\BitbucketApi';
	}

	public function getWrapperNameWithLowApiUsageLimit()
	{
		return '\CodingPride\Tests\Repository\BitbucketApiLowLimit';
	}

	public static function getJsonCommitDetails()
	{
		return file_get_contents( __DIR__ . '/bitbucket_commit_details.json' );
	}

	public static function getJsonResponseFromApi()
	{
		return file_get_contents( __DIR__ . '/bitbucket_latest_commits.json' );
	}
}
<?php
namespace CodingPride\Source\Tests;

require_once __DIR__ . '/AbstractApiWrapper.php';

class BitbucketApiWrapperTest extends AbstractApiWrapperTest
{
	public function getWrapperName()
	{
		return '\CodingPride\Source\BitbucketApiWrapper';
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
<?php
namespace CodingPride\Tests\Repository;

require_once __DIR__ . '/AbstractApi.php';
require_once __DIR__ . '/FisheyeApiLowLimit.php';

class FisheyeApiTest extends AbstractApiTest
{
	public function getWrapperName()
	{
		return '\CodingPride\Repository\FisheyeApi';
	}

	public function getWrapperNameWithLowApiUsageLimit()
	{
		return '\CodingPride\Tests\Repository\FisheyeApiLowLimit';
	}

	public static function getJsonCommitDetails()
	{
		return file_get_contents( __DIR__ . '/fisheye_commit_details.json' );
	}

	public static function getJsonResponseFromApi()
	{
		return file_get_contents( __DIR__ . '/fisheye_latest_commits.json' );
	}
	
}
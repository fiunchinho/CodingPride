<?php
namespace CodingPride\Source\Tests;

require_once __DIR__ . '/AbstractApiWrapper.php';

class FisheyeApiWrapperTest extends AbstractApiWrapperTest
{
	public function getWrapperName()
	{
		return '\CodingPride\Source\FisheyeApiWrapper';
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
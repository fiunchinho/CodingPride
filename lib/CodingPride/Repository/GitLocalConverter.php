<?php
namespace CodingPride\Repository;

class GitLocalConverter implements ConverterInterface
{
	public function convert( $commit_info_from_cli )
	{
		$commit = new \CodingPride\Document\Commit();
		$commit->setAuthorUsername( trim( $commit_info_from_cli[1] ) );
		$commit->setDate( new \DateTime( $commit_info_from_cli[2] ) );
		$commit->setRevision( trim( $commit_info_from_cli[0] ) );
		$commit->setComment( trim( $commit_info_from_cli[3] ) );

		$files_raw = array_filter ( explode( "\n", $commit_info_from_cli[4] ) );

		$files = array();

		foreach ( $files_raw as $file_path )
		{
			$file = new \CodingPride\File( $file_path );
			$files[] = $file;
		}

		$commit->setFiles( $files );

		return $commit;
	}

	public function getRevision( $commit_info )
	{
		return $commit_info['0'];
	}
}

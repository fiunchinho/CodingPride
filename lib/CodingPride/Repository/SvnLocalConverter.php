<?php
namespace CodingPride\Repository;

class SvnLocalConverter implements ConverterInterface
{
	public function convert( $commit_info_from_cli )
	{
		$commit = new \CodingPride\Document\Commit();
		$commit->setAuthorUsername( $commit_info_from_cli['author'] );
		$commit->setDate( new \DateTime( $commit_info_from_cli['date'] ) );
		$commit->setRevision( $commit_info_from_cli['rev'] );
		$commit->setComment( $commit_info_from_cli['msg'] );

		$files = array();

		foreach ( $commit_info_from_cli['paths'] as $file_info )
		{
			$file = new \CodingPride\File( $file_info['path'] );
			//$file->setModificationType( $commit_file['action'] );
			$files[] = $file;
		}

		$commit->setFiles( $files );

		return $commit;
	}

	public function getRevision( $commit_info )
	{
		return $commit_info['rev'];
	}
}

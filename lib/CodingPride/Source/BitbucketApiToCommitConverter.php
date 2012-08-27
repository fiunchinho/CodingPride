<?php
namespace CodingPride\Source;

class BitbucketApiToCommitConverter implements ConverterInterface
{
	public function convert( array $commit_info )
	{
		$commit = new \CodingPride\Document\Commit();
		$commit->setAuthorUsername( $commit_info['author'] );
		$commit->setDate( new \DateTime( $commit_info['timestamp'] ) );
		$commit->setRevision( $commit_info['raw_node'] );
		$commit->setComment( $commit_info['message'] );
		$commit->setParent( $commit_info['parents']['0'] );

		$files = array();

		foreach ( $commit_info['files'] as $commit_file )
		{
			$file = new \CodingPride\File( $commit_file['file'] );
			//$file->setModificationType( $commit_file['type'] );
			$files[] = $file;
		}
		$commit->setFiles( $files );

		return $commit;
	}

	public function getRevision( array $commit_info )
	{
		return $commit_info['raw_node'];
	}
}

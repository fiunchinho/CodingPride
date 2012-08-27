<?php
namespace CodingPride\Source;

class GithubApiToCommitConverter implements ConverterInterface
{
	public function convert( array $commit_info )
	{
		$commit = new \CodingPride\Document\Commit();
		$commit->setAuthorUsername( $commit_info['author']['login'] );
		$commit->setDate( new \DateTime( $commit_info['commit']['author']['date'] ) );
		$commit->setRevision( $commit_info['sha'] );
		$commit->setComment( $commit_info['commit']['message'] );

		$files = array();

		foreach ( $commit_info['files'] as $file )
		{
			$files[] = new \CodingPride\File( $file['filename'] );
		}
		$commit->setFiles( $files );

		return $commit;
	}

	public function getRevision( array $commit_info )
	{
		return $commit_info['sha'];
	}
}

<?php
namespace CodingPride\Source;

class FisheyeApiToCommitConverter implements ConverterInterface
{
	public function convert( $commit_info_from_api )
	{
		$commit_info 	= json_decode( $commit_info_from_api, true );
		// Hack to convert from microtime to time format.
		$date = substr( $commit_info['date'], 0, -3 );
		$commit = new \CodingPride\Document\Commit();
		$commit->setAuthorUsername( $commit_info['author'] );
		$commit->setDate( new \DateTime( date( 'Y-m-d H:i:s', $date ) ) );
		$commit->setRevision( $commit_info['csid'] );
		$commit->setComment( $commit_info['comment'] );
		$commit->setBranch( $commit_info['branch'] );
		$commit->setParent( $commit_info['parents']['0'] );

		$files = array();

		foreach ( $commit_info['fileRevisionKey'] as $commit_file )
		{
			$file = new \CodingPride\File( $commit_file['path'] );
			//$file->setModificationType( $commit_file['type'] );
			$files[] = $file;
		}
		$commit->setFiles( $files );

		return $commit;
	}

	public function getRevision( $commit_info )
	{
		return $commit_info;
	}
}

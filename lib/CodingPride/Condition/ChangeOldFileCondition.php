<?php
namespace CodingPride\Condition;

class ChangeOldFileCondition extends AbstractDatabaseAwareCondition
{
	const DAYS_WIHTOUT_COMMIT = 7;

	public function check( \CodingPride\Document\Commit $commit )
	{
		foreach ( $commit->getFiles() as $file )
		{
			$criteria = array(
				'date' 		=> array( '$lt' 		=> $commit->getDate() ),
				'files' 	=> array( '$elemMatch' 	=> array( 'path' => $file->getPath() ) )
			);
			$commits_with_this_file = $this->_dm
				->getRepository( '\CodingPride\Document\Commit' )
				->findBy( $criteria );
			
			if ( $commits_with_this_file->hasNext() )
			{
				$commit_to_check_against = $commits_with_this_file->getNext();
				if ( $commit->getDate()->diff( $commit_to_check_against->getDate() )->days >= self::DAYS_WIHTOUT_COMMIT )
				{
					return true;
				}
			}
			return false;			
		}
	}
}
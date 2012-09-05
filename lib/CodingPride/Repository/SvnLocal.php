<?php
namespace CodingPride\Repository;

class SvnLocal extends Repository
{
	/**
	 * The class name of the converter to use.
	 */
	const CONVERTER_CLASS_NAME  = '\CodingPride\Repository\SvnLocalConverter';

	/**
	 * Get the commit list through the console, using a PHP extension (php-svn), and converts it to a CommitList object.
	 * Uses a converter to convert the raw console response to a Commit object.
	 * This method also tries to save the commit in database. If it's already there, it won't be include
	 * in the CommitList object.
	 *
	 * @return \CodingPride/CommitList The list of commits
	 */
	public function getCommits()
	{
		$commit_collection		= new \CodingPride\CommitList();
		$commit_repository		= $this->_dm->getRepository( 'CodingPride\Document\Commit' );

		$converter 				= $this->getConverter();
		$commits 				= $this->getCommitsFromConsole();

		foreach ( $commits as $commit_raw )
		{
			$commit 		= $converter->convert( $commit_raw );
			$is_commit_new	= $commit_repository->create( $commit );

			if ( $is_commit_new )
			{
				$commit_collection[] = $commit;	
			}
		}

		return $commit_collection;
	}

	/**
	 * Executes a console command to get info from latest commits.
	 * It uses the following PHP extension: http://www.php.net/manual/es/book.svn.php
	 *
	 * @return string The console output
	 */
	private function getCommitsFromConsole()
	{
		return svn_log( $this->config['repository'], SVN_REVISION_HEAD, SVN_REVISION_INITIAL );
	}
}
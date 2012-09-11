<?php
namespace CodingPride\Repository;

class GitLocal extends Repository
{
	/**
	 * The class name of the converter to use.
	 */
	const CONVERTER_CLASS_NAME  = '\CodingPride\Repository\GitLocalConverter';

	/**
	 * It will use this string to split the console output into separate commits.
	 */
	const COMMIT_START_PLACEHOLDER 	= '{-start-}';

	/**
	 * Get the commit list through the console, and converts it to a CommitList object. It needs to
	 * use a converter to convert the raw console response to a Commit object.
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

		$commits				= $this->cleanCommitsOutput( $this->getCommitsFromConsole() );	
		
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
	 * Since the console output is not a clean list of commits, we need to prepare the output so we can treat it.
	 * This method split the string to get the commit data.
	 *
	 */
	protected function cleanCommitsOutput( $commits_output )
	{
		$explode_pipes 	= function( $arr ){ return explode( '||', $arr ); };
		$commits 		= array_map( $explode_pipes, explode( self::COMMIT_START_PLACEHOLDER, $commits_output ) );
		array_shift( $commits );
		return $commits;
	}

	/**
	 * Returns the console command that it will execute to get the list of commits.
	 * @return string The git command
	 *
	 */
	protected function getCommitsFromConsole()
	{
		return shell_exec( 'git --git-dir=' . $this->config['repository'] . ' log --name-only --pretty=format:"' . self::COMMIT_START_PLACEHOLDER . ' %H || %an || %ad || %s ||"' );
	}
}
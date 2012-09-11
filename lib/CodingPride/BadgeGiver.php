<?php
namespace CodingPride;

use \CodingPride\Document\Commit;

class BadgeGiver
{
	/**
	 * Checks if the authors of the given commits, deserve the given badges.
	 *
	 * @param \Iterator $commit_list The list of commits to check
	 * @param \Iterator $badges The badges that we want to give
	 *
	 */
	public function giveBadges( \Iterator $commit_list, \Iterator $badges )
	{
		foreach( $commit_list as $commit )
		{
			$this->giveBadgesForThisCommit( $badges, $commit );
		}
	}
	
	/**
	 * Checks if the authors of the given commit, deserves any of the given badges.
	 *
	 * @param \Iterator $badges The badges that we want to give
	 * @param Commit $commit The commits to check
	 *
	 */
	protected function giveBadgesForThisCommit( \Iterator $badges, Commit $commit )
	{
		foreach ( $badges as $badge )
		{
			$commit->setIn_game( 1 );
			if ( $badge->check( $commit ) )
			{
				$commit->getAuthor()->addBadge( $badge );
			}
		}
	}
}
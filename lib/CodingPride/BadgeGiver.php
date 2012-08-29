<?php
namespace CodingPride;

class BadgeGiver
{
	public function giveBadges( \Iterator $commit_list, \Iterator $badges )
	{
		foreach( $commit_list as $commit )
		{
			$this->giveBadgesForThisCommit( $badges, $commit );
		}
	}
	
	protected function giveBadgesForThisCommit( $badges, $commit )
	{
		foreach ( $badges as $badge )
		{
			if ( $badge->check( $commit ) )
			{
				$author = $commit->getAuthor();
				$author->addBadge( $badge );
			}

			$badge->setLast_pagination_param( $commit->getRevision() );
		}
	}
}
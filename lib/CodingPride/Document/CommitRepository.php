<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * CommitRepository
 *
 */
class CommitRepository extends DocumentRepository
{
	public function create( Commit $commit )
	{
		$commit_from_database = $this->findOneBy( array( 'revision' => $commit->getRevision() ) );

		if ( !empty( $commit_from_database ) )
		{
			return false;
		}

		$user_repository	= $this->dm->getRepository( '\CodingPride\Document\User' );
		$author				= $user_repository->create( $commit->getAuthorUsername() );
		$commit->setAuthor( $author );
		
		$this->dm->persist( $commit );
		$this->dm->flush();

		return $commit;
	}
}
<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * CommitRepository
 *
 */
class CommitRepository extends DocumentRepository
{
	public function create( array $commit_info = array(), \CodingPride\Source\ConverterInterface $converter = null )
	{
		$revision 	= $converter->getRevision( $commit_info );
		$commit		= $this->findOneBy( array( 'revision' => $revision ) );

		if ( !empty( $commit ) )
		{
			return false;
		}
		
		$commit 			= $converter->convert( $commit_info );
		$user_repository	= $this->dm->getRepository( '\CodingPride\Document\User' );
		$author				= $user_repository->create( $commit->getAuthorUsername() );

		$commit->setAuthor( $author );

		$this->dm->persist( $commit );
		$this->dm->flush();

		return $commit;
	}
}
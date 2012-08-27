<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * UserRepository
 *
 */
class UserRepository extends DocumentRepository
{
	protected $users_already_requested = array();

	public function create( $username )
	{
		if ( array_key_exists( $username, $this->users_already_requested ) )
		{
			return $this->users_already_requested[$username];
		}

		$user = $this->findOneBy( array( 'username' => $username ) );

		if ( empty( $user ) )
		{
			$user = new User();
			$user->setUsername( $username );
			$this->dm->persist( $user );
		}

		$this->users_already_requested[$username] = $user;

		return $user;
	}
}
<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * UserRepository
 *
 */
class UserRepository extends DocumentRepository
{
	/**
	 * Keeps track of users already treated by the system so we don't have to query the DB again.
	 * @var array $users_already_requested
	 */
	protected $users_already_requested = array();

	/**
	 * Tries to find the user in the database. If it already exists it returns it.
	 * If the user is not in the database, it creates it and save it to the
	 * registry of users, so we don't have to query the database again.
	 *
	 * @param string $username The username of the user to create
	 * @return User
	 *
	 */
	public function create( $username )
	{
		if ( array_key_exists( $username, $this->users_already_requested ) )
		{
			return $this->users_already_requested[$username];
		}

		$user = $this->findOneBy( array( 'username' => $username ) );

		if ( empty( $user ) )
		{
			$user = $this->createNewUser( $username );
		}

		$this->users_already_requested[$username] = $user;

		return $user;
	}

	/**
	 * Creates a new User instance and tells the database manager to persist it.
	 * @param string $username The username of the user to create
	 * @return User
	 *
	 */
	protected function createNewUser( $username )
	{
		$user = new User();
		$user->setUsername( $username );
		$this->dm->persist( $user );
		return $user;
	}
}
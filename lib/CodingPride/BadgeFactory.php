<?php
namespace CodingPride;

class BadgeFactory
{
	/**
	 * Active badges are those up to date.
	 * @var array $badges
	 *
	 */
	protected $badges;

	/**
	 * Inactive badges are those that the system has not gone through the old
	 * commits yet to check if that particular badge needs to be given.
	 * @var array $inactive_badges
	 *
	 */
	protected $inactive_badges;
	
	/**
	 * @param DocumentManager $database_manager Manager to save and retrieve records from database
	 * @param array $badges_from_config The badges that we want to have in to the game
	 *
	 */
	public function __construct( $database_manager, array $badges_from_config )
	{
		$this->_dm 					= $database_manager;
		$this->badges_from_config 	= $badges_from_config;
	}

	/**
	 * Given the names of the conditions, it returns an array with condtion objects
	 * that represents those conditions
	 *
	 * @param array $condition_names The names of the conditions to instantiate
	 * @return array The conditions to set to the badge
	 *
	 */
	protected function createConditions( array $conditions_names )
	{
		$conditions = array();

		foreach ( $conditions_names as $key => $condition_name )
		{
			$class_name 	= '\\CodingPride\\Condition\\' . $condition_name;
			$conditions[] 	= new $class_name( $this->_dm );
		}

		return $conditions;
	}

	/**
	 * Takes the badges from the config file and try to insert them in database if
	 * they are not there yet. The repository returns the created badge, that could come
	 * from database if it was already there.
	 *
	 * By default, a badge is inactive.
	 * This method creates two arrays for keeping active and inactive badges.
	 *
	 * @param array $badges_from_config The badges that we want in the game
	 *
	 */
	protected function setBadges( array $badges_from_config )
	{
		$active_badges		= $inactive_badges = array();

		foreach ( $badges_from_config as $badge_name => $badge_details )
		{
			$conditions 	= $this->createConditions( $badge_details['conditions'] );
			$badge 			= $this
				->_dm
				->getRepository( 'CodingPride\Document\Badge' )
				->create( $badge_name, $conditions, $badge_details['description'] );

			if ( $badge->isActive() )
			{
				$active_badges[] = 	$badge;
			}
			else
			{
				$inactive_badges[] = $badge;
			}
		}

		$this->badges 			= new BadgeCollection( $active_badges );
		$this->inactive_badges 	= new BadgeCollection( $inactive_badges );
		$this->_dm->flush();
	}

	/**
	 * Active badges are those up to date.
	 *
	 * @return array The badges
	 *
	 */
	public function getBadges()
	{
		if ( empty( $this->badges ) )
		{
			$this->setBadges( $this->badges_from_config );
		}

		return $this->badges;
	}

	/**
	 * Inactive badges are those that the system has not gone through the old
	 * commits yet to check if that particular badge needs to be given.
	 *
	 * @return array The inactive badges
	 *
	 */
	public function getInactiveBadges()
	{
		if ( empty( $this->inactive_badges ) )
		{
			$this->setBadges( $this->badges_from_config );
		}

		return $this->inactive_badges;
	}
	
}
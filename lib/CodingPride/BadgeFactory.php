<?php
namespace CodingPride;

class BadgeFactory
{
	protected $badges;
	
	public function __construct( $database_manager, array $badges_from_config )
	{
		$this->_dm 					= $database_manager;
		$this->badges_from_config 	= $badges_from_config;
	}

	protected function createConditions( $conditions_names )
	{
		$conditions = array();

		foreach ( $conditions_names as $key => $condition_name )
		{
			$class_name 	= '\\CodingPride\\Condition\\' . $condition_name;
			$conditions[] 	= new $class_name();
		}

		return $conditions;
	}

	protected function setBadges()
	{
		$badges_array		= array();


		foreach ( $this->badges_from_config as $badge_name => $badge_details )
		{
			$conditions 	= $this->createConditions( $badge_details['conditions'] );
			$badges_array[] = $this->_dm->getRepository( 'CodingPride\Document\Badge' )->create( $badge_name, $conditions, $badge_details['description'] );
		}

		$this->badges = new BadgeCollection( $badges_array );
		$this->_dm->flush();
	}

	public function getBadges()
	{
		if ( empty( $this->badges ) )
		{
			$this->setBadges();
		}

		return $this->badges;
	}
	
}
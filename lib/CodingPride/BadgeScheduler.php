<?php
namespace CodingPride;

class BadgeScheduler
{
	const DEFAULT_BADGE_DATE = '2007-03-15';
	protected $_dm;
	public $badges = array();

	public function __construct( $database_manager )
	{
		$this->_dm = $database_manager;
	}

	public function insertNewBadgesInDb( array $badges_from_config )
	{
		$new_badges = $this->filterNewBadges( $badges_from_config );

		foreach ( $new_badges as $badge_name => $badge_details )
		{
			$badge = new Badge( $badge_name );
			$badge->setName( $badge_name );
			$badge->setLast_date_checked( new \DateTime( self::DEFAULT_BADGE_DATE ) );
			$badge->setConditions( $badge_details['conditions'] );
			$badge->setDescription( $badge_details['description'] );
			$this->_dm->persist( $badge );
			var_dump( "Persistiendo " . $badge->getName() );
		}
	}

	protected function filterNewBadges( array $badges_from_json )
	{
		$badges_in_db 	= $this->_dm->getRepository( 'CodingPride\Document\Badge' )->findAll();
		foreach ( $badges_in_db as $badge )
		{
			unset( $badges_from_json[$badge->getName()] );
		}
		return $badges_from_json;
	}

	public function updateScheduleInBadges()
	{
		$badges_collection = $this->getDb()->badge;
		$badges_in_db = iterator_to_array( $badges_collection->find() );

		foreach ( $badges_in_db as $badge )
		{
			$badge_last_update = new \DateTime( (string) $badge['last_date_checked'] );
			$this->getDb()->update(
				array( 'name' => $badge ),
				array(
					'name' 				=> $badge,
					'last_date_checked' => $badge_last_update->modify( '+1 day' )->format( 'Y-m-d' )
				)
			);
		}
	}

	public function getBadgesOutOfDate( array $badges_to_calculate, \DateTime $date )
	{
		return $this->_dm->getRepository( 'CodingPride\Document\Badge' )->getBadgesOutOfDate( $badges_to_calculate, $date );
	}

	/*
	public function getLastDateChecked()
	{
		$db 		= $this->getDb()->scheduler;
		$scheduler 	= iterator_to_array( $db->find() );
		return new DateTime( (string) $scheduler['last_date_checked'] );
	}

	public function setLastDateChecked( \DateTime $date )
	{
		$db = $this->getDb()->scheduler;
		return $db->update( $db->find(), array( 'last_date_checked' => $date->format( 'Y-m-d' ) ) );
	}

	public function setBadgeLastDateChecked( $badge, \DateTime $date )
	{
		$badge->setLast_date_checked( $date );
		return $this->_dm->persist( $badge );
	}
	*/
}
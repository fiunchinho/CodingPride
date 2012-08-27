<?php
namespace CodingPride;

class Gamificator
{
	public function __construct( $api, $badge_collection )
	{
		$this->api 					= $api;
		$this->badge_collection 	= $badge_collection;
	}

	public function gamify( $pagination_param = '' )
	{
		$badge_giver 				= new BadgeGiver();
		$badge_giver->giveBadges( $this->api->getLatestCommits(), $this->badge_collection );
	}
}
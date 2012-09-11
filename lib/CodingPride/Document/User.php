<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/** @ODM\Document(collection="user", repositoryClass="CodingPride\Document\UserRepository") */
class User
{
	/** @ODM\Id */
	private $id;

	/** @ODM\String */
	private $username;

	/** @ODM\ReferenceMany(targetDocument="Badge", cascade="all") */
	protected $badges;

	public function __construct( $username = null )
	{
		if ( !empty( $username ) )
		{
			$this->setUsername( $username );
		}
		$this->badges = new ArrayCollection();
	}

	/**
	 * Filters the given array of badges, to leave just the badges that
	 * the user didn't earn yet.
	 *
	 * @param array $badges All the badges in the system
	 * @return array $badges The badges that the user didnt earn yet
	 *
	 */
	public function getUserBadgesToAchieve( array $badges )
	{
		if ( !empty( $this->badges ) )
		{
			foreach( $this->getBadges() as $badge )
			{
				unset( $badges[$badge->getName()] );
			}
		}

		return $badges;
	}

	/**
     * Add badge
     *
     * @param \CodingPride\Document\Badge $badge
     * @return User
     */
    public function addBadge( \CodingPride\Document\Badge $badge )
    {
    	if ( !$this->badges->contains( $badge ) )
    	{
    		$this->badges[] = $badge;
    	}
        
        return $this;
    }

    /**
     * Get badges
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBadges()
    {
        return $this->badges;
    }

	public function setUsername( $username )
	{
		$this->username = $username;
		return $this;
	}

	public function getUsername()
	{
		return $this->username;
	}
}
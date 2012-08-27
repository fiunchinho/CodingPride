<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

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
		$this->badges = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * There's probably a better solution for this instead of calling iterator_to_array.
	 *
	 */
	public function getUserBadgesToAchieve( array $badges )
	{
		//var_dump( $badges, empty( $this->badges ), $this->badges  );die;

		if ( !empty( $this->badges ) )
		{
			foreach( $this->getBadges() as $badge )
			{
				unset( $badges[$badge->getName()] );
				/*
				if ( in_array( $badge,  iterator_to_array( $this->badges ) ) )
				{
					unset( $badges[$key] );
				}*/
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
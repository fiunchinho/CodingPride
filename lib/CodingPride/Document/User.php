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

	/** @ODM\ReferenceMany(targetDocument="Badge", cascade="all", simple=true) */
	protected $badges;

	/** @ODM\ReferenceMany(targetDocument="Commit", cascade="all", simple=true) */
	protected $commits;

	public function __construct( $username = null )
	{
		if ( !empty( $username ) )
		{
			$this->setUsername( $username );
		}
		$this->badges 	= new ArrayCollection();
		$this->commits 	= new ArrayCollection();
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
     * Remove a badge
     *
     * @param \CodingPride\Document\Badge $badge
     * @return bool
     */
    public function removeBadge( \CodingPride\Document\Badge $badge )
    {
    	return $this->badges->removeElement( $badge );
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

    /**
     * Add commit
     *
     * @param \CodingPride\Document\Commit $commit
     * @return User
     */
    public function addCommit( \CodingPride\Document\Commit $commit )
    {
    	if ( !$this->commits->contains( $commit ) )
    	{
    		$this->commits[] = $commit;
    	}
        
        return $this;
    }

    /**
     * Get commits
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCommits()
    {
        return $this->commits;
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
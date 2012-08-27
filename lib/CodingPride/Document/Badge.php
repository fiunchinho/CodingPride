<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="badge", repositoryClass="CodingPride\Document\BadgeRepository")
 */
class Badge
{
	/** @ODM\Id */
	private $id;

	/** @ODM\String */
	private $name;

	/** @ODM\String */
	private $description;

	private $conditions = array();

	/** @ODM\String */
	private $last_pagination_param;

	/** @ODM\Int */
	private $active = 1;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName( $name )
	{
		$this->name = $name;
		return $this;
	}

	public function setDescription( $description )
	{
		$this->description = $description;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setConditions( array $conditions )
	{
		$this->conditions = $conditions;
		return $this;
	}

	public function getConditions()
	{
		return $this->conditions;
	}
	public function check( Commit $commit )
	{
		foreach ( $this->getConditions() as $condition )
		{
			if ( !$condition->check( $commit ) )
			{
				return false;
			}
		}
		return true;
	}
	public function getLast_pagination_param()
	{
		return $this->last_pagination_param;
	}
	public function setLast_pagination_param( $param )
	{
		$this->last_pagination_param = $param;
		return $this;
	}

	public function __toString()
	{
		return $this->name;
	}
}
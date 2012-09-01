<?php
namespace CodingPride\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="commit", repositoryClass="CodingPride\Document\CommitRepository") */
class Commit
{
	/** @ODM\Id */
	private $id;
	
	/** @ODM\ReferenceOne(targetDocument="User") */
	private $author;

	private $author_username;

	/** @ODM\Date */
	private $date;

	/** @ODM\String */
	private $branch;

	/** @ODM\String */
	private $revision;

	/**
	 * @ODM\Hash
	 *	Files involved in this commit.
	 */
	protected $files;

	/** @ODM\String
	 *	Commit comment, normally JIRA issue ID and title.
	 */
	private $comment;

	/** @ODM\Int
	 *	The previous commit to this branch. The first commit won't have parent.
	 */
	private $parent;

	/** @ODM\Int
	 *	The next commit to this branch. The latest commit won't have child.
	 */
	private $child;

	/** @ODM\PostLoad */
    public function postLoad()
    {
        $files = array();

		foreach ( $this->files as $file )
		{
			$files[] = new \CodingPride\File( $file['path'] );
		}
		$this->setFiles( $files );
    }

	public function getDate()
	{
		return $this->date;
	}

	public function setDate( \DateTime $date )
	{
		$this->date = $date;
		return $this;
	}

	public function getFiles()
	{
		return $this->files;
	}

	public function setFiles( array $files )
	{
		return $this->files = $files;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor( User $author )
	{
		$this->author = $author;
		return $this;
	}

	public function getBranch()
	{
		return $this->branch;
	}

	public function setBranch( $branch )
	{
		$this->branch = $branch;
		return $this;
	}

	public function getRevision()
	{
		return $this->revision;
	}

	public function setRevision( $revision )
	{
		$this->revision = $revision;
		return $this;
	}

	public function getComment()
	{
		return $this->comment;
	}

	public function setComment( $comment )
	{
		$this->comment = $comment;
		return $this;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function setParent( $parent )
	{
		$this->parent = $parent;
		return $this;
	}

	public function getChild()
	{
		return $this->child;
	}

	public function setChild( $child )
	{
		$this->child = $child;
		return $this;
	}

	public function getAuthorUsername()
	{
		return $this->author_username;
	}

	public function setAuthorUsername( $username )
	{
		$this->author_username = $username;
		return $this;
	}

}
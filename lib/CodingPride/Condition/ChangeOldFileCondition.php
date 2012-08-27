<?php
namespace CodingPride\Condition;

//require_once 'ConditionInterface.php';

class ChangeOldFileCondition implements ConditionInterface
{
	public $file_extension;

	public function check( \CodingPride\Document\Commit $commit )
	{
		foreach ( $commit->getFiles() as $file )
		{
			if ( $this->time_without_changes <= $file->getLastModified() - $now )
			{
				return true;
			}
		}
		return false;
	}
}
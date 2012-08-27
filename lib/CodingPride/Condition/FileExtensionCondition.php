<?php
namespace CodingPride\Condition;

//require_once 'ConditionInterface.php';

class FileExtensionCondition implements ConditionInterface
{
	public $file_extension;

	public function check( \CodingPride\Document\Commit $commit )
	{
		foreach ( $commit->getFiles() as $file )
		{
			if ( $this->file_extension == $file->getExtension() )
			{
				return true;
			}
		}
		return false;
	}
}
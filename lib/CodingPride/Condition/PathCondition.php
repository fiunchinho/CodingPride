<?php
namespace CodingPride\Condition;

abstract class PathCondition implements ConditionInterface
{
	protected $path_to_match;

	public function check( \CodingPride\Document\Commit $commit )
	{
		foreach ( $commit->getFiles() as $file )
		{
			if ( false !== strpos( $file->getPath(),  $this->path_to_match ) )
			{
				return true;
			}
		}
		return false;
	}
}

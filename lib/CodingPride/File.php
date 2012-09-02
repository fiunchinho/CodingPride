<?php
namespace CodingPride;
/**
 * Represents a file affected by a commit.
 */
class File
{
	public function __construct( $path )
	{
		$this->path 	= $path;
		$this->info 	= pathinfo( $this->path );
	}
	public function getExtension()
	{
		if ( !empty( $this->info['extension'] ) )
		{
			return $this->info['extension'];
		}
		return false;
	}
	public function getPath()
	{
		return $this->path;
	}
}
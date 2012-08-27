<?php
namespace CodingPride;
/**
 * Represents a file affected by a commit.
 */
class File
{
	public function __construct( $path )
	{
		//$attributes 	= $xml->attributes();
		//$this->path 	= $attributes['path'];
		$this->path 	= $path;
		$this->info 	= pathinfo( $this->path );
		//$this->revision = $attributes['rev'];
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
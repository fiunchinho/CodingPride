<?php
namespace CodingPride;

class CommitList implements \ArrayAccess, \Iterator, \Countable
{
	private $commits;

	public function __construct()
	{
		$this->commits  = array();
	}

	public function offsetSet( $offset, $value )
	{
        if ( is_null( $offset ) )
        {
			$this->commits[] = $value;
        }
        else
        {
			$this->commits[$offset] = $value;
        }
    }

    public function offsetExists( $offset )
    {
		return isset( $this->commits[$offset] );
    }

    public function offsetUnset( $offset )
    {
		unset( $this->commits[$offset] );
    }

    public function offsetGet( $offset )
    {
    	return isset( $this->commits[$offset] ) ? $this->commits[$offset] : null;
    }

    public function rewind()
    {
        reset($this->commits);
    }

    public function current()
    {
        return current($this->commits);
    }

    public function key()
    {
        return key($this->commits);
    }

    public function next()
    {
        return next($this->commits);
    }

    public function valid()
    {
        return $this->current() !== false;
    }    

    public function count()
    {
        return count($this->commits);
    }

    /**
     * Combines the two CommitList objects in only one.
     *
     */
    public function combine( CommitList $commits )
    {
        $this->commits = array_merge( $this->commits, $commits->commits );
    }

}
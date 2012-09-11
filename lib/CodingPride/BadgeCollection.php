<?php
namespace CodingPride;

class BadgeCollection extends \ArrayIterator
{
	/**
	 * Go through all the badges in the collection and active every badge.
	 * @return BadgeCollection
	 *
	 */
	public function activateAll()
	{
		foreach ( $this as $badge )
        {
            $badge->setActive( 1 );
        }

        return $this;
	}
}
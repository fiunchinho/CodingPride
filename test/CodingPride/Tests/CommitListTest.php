<?php
namespace CodingPride\Tests;

class CommitListTest extends \PHPUnit_Framework_TestCase
{
	public function testBehavesLikeArray()
	{
		$commit_list 	= new \CodingPride\CommitList();
		$commit_list[] 	= 1;
		$commit_list[1] = 2;
		$counter = 0;
		foreach ( $commit_list as $number )
		{
			$counter++;
		}
		$this->assertEquals( 2, $counter );
		$this->assertTrue( isset( $commit_list[0] ) );
	}

	public function testCombine()
	{
		$commit_list 	= new \CodingPride\CommitList();
		$commit_list[0]	= 1;
		$commit_list[1] = 2;

		$commit_list2 	= new \CodingPride\CommitList();
		$commit_list2[0]= 3;
		$commit_list2[1]= 4;

		$commit_list->combine( $commit_list2 );
		$this->assertEquals( 4, count( $commit_list ), 'The number of items must be the combined amount' );
		$this->assertEquals( $commit_list[2], $commit_list2[0], 'The items must be combined' );
		$this->assertEquals( $commit_list[3], $commit_list2[1], 'The items must be combined' );
	}
}
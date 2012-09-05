<?php
namespace CodingPride\Repository;

interface ConverterInterface
{
	public function convert( $commit_info );

	public function getRevision( $commit_info );
}

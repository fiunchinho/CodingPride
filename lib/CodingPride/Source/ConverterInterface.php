<?php
namespace CodingPride\Source;

interface ConverterInterface
{
	public function convert( $commit_info );

	public function getRevision( $commit_info );
}

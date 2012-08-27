<?php
namespace CodingPride\Source;

interface ConverterInterface
{
	public function convert( array $commit_info );

	public function getRevision( array $commit_info );
}

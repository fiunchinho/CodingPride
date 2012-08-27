<?php
namespace CodingPride\Condition;

interface ConditionInterface
{
	public function check( \CodingPride\Document\Commit $commit );
}
<?php
namespace CodingPride\Condition;

class Reach500CommitsCondition extends NumberOfCommitsCondition
{
	protected $number_to_reach = 500;
}
<?php
namespace CodingPride\Condition;

class Reach500CommitsCondition extends NumberOfCommitsCondition
{
	public $number_to_reach = 500;
}
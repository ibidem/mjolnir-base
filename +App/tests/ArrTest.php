<?php namespace mjolnir\base\tests;

use \mjolnir\base\Arr;

class ArrTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Arr'));
	}

	// @todo tests for \mjolnir\base\Arr

} # test

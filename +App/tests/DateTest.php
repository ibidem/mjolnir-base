<?php namespace mjolnir\base\tests;

use \mjolnir\base\Date;

class DateTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Date'));
	}

	// @todo tests for \mjolnir\base\Date

} # test

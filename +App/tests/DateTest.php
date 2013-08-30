<?php namespace mjolnir\base\tests;

use \mjolnir\base\Date;

class DateTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Date'));
	}

	/** @test */ function
	works_with_typical_timezone()
	{
		$timezone = \date_default_timezone_get();
		\date_default_timezone_set('America/Los_Angeles');
		$this->assertEquals(\app\Date::default_timezone_offset(), '-7:00');
		\date_default_timezone_set($timezone);
	}

} # test

<?php namespace mjolnir\base\tests;

use \mjolnir\base\Debug;

class DebugTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Debug'));
	}

	// @todo tests for \mjolnir\base\Debug

} # test

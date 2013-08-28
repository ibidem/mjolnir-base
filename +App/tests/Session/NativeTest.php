<?php namespace mjolnir\base\tests;

use \mjolnir\base\Session_Native;

class Session_NativeTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Session_Native'));
	}

	// @todo tests for \mjolnir\base\Session_Native

} # test

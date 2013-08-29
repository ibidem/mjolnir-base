<?php namespace mjolnir\base\tests;

use \mjolnir\base\Cookie;

class CookieTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Cookie'));
	}

	// @todo tests for \mjolnir\base\Cookie

} # test

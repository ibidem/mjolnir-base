<?php namespace mjolnir\base\tests;

use \mjolnir\base\Session;

class SessionTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Session'));
	}

	// @todo tests for \mjolnir\base\Session

} # test

<?php namespace mjolnir\base\tests;

use \mjolnir\base\Email;

class EmailTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Email'));
	}

	// @todo tests for \mjolnir\base\Email

} # test

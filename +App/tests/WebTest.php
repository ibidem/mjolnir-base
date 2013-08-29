<?php namespace mjolnir\base\tests;

use \mjolnir\base\Web;

class WebTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Web'));
	}

	// @todo tests for \mjolnir\base\Web

} # test

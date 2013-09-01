<?php namespace mjolnir\base\tests;

use \mjolnir\base\Controller;

class ControllerTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Controller'));
	}

	// no tests required

} # test

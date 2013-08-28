<?php namespace mjolnir\base\tests;

use \mjolnir\base\Controller;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Controller'));
	}

	// @todo tests for \mjolnir\base\Controller

} # test

<?php namespace mjolnir\base\tests;

use \mjolnir\base\Controller_Base;

class Controller_BaseTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Controller_Base'));
	}

	// @todo tests for \mjolnir\base\Controller_Base

} # test

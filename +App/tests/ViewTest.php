<?php namespace mjolnir\base\tests;

use \mjolnir\base\View;

class ViewTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\View'));
	}

	// @todo tests for \mjolnir\base\View

} # test

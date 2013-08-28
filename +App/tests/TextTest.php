<?php namespace mjolnir\base\tests;

use \mjolnir\base\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Text'));
	}

	// @todo tests for \mjolnir\base\Text

} # test

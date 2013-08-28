<?php namespace mjolnir\base\tests;

use \mjolnir\base\Lang;

class LangTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Lang'));
	}

	// @todo tests for \mjolnir\base\Lang

} # test

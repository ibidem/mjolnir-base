<?php namespace mjolnir\base\tests;

use \mjolnir\base\Shell;

class ShellTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Shell'));
	}

	// @todo tests for \mjolnir\base\Shell

} # test

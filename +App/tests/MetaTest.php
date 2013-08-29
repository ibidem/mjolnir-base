<?php namespace mjolnir\base\tests;

use \mjolnir\base\Meta;

class MetaTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Meta'));
	}

	// @todo tests for \mjolnir\base\Meta

} # test

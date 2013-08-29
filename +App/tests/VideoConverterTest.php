<?php namespace mjolnir\base\tests;

use \mjolnir\base\VideoConverter;

class VideoConverterTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\VideoConverter'));
	}

	// @todo tests for \mjolnir\base\VideoConverter

} # test

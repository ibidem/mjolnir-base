<?php namespace mjolnir\base\tests;

use \mjolnir\base\Media;

class MediaTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Media'));
	}

	// @todo tests for \mjolnir\base\Media

} # test

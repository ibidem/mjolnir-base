<?php namespace mjolnir\base\tests;

use \mjolnir\base\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Image'));
	}

	// @todo tests for \mjolnir\base\Image

} # test

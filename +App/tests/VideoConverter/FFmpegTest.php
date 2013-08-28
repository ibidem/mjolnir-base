<?php namespace mjolnir\base\tests;

use \mjolnir\base\VideoConverter_FFmpeg;

class VideoConverter_FFmpegTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\VideoConverter_FFmpeg'));
	}

	// @todo tests for \mjolnir\base\VideoConverter_FFmpeg

} # test

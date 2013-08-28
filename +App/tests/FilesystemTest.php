<?php namespace mjolnir\base\tests;

use \mjolnir\base\Filesystem;

class FilesystemTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Filesystem'));
	}

	// @todo tests for \mjolnir\base\Filesystem

} # test

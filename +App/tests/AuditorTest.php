<?php namespace mjolnir\base\tests;

use \mjolnir\base\Auditor;

class AuditorTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Auditor'));
	}

	// @todo tests for \mjolnir\base\Auditor

} # test

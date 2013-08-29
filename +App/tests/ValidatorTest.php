<?php namespace mjolnir\base\tests;

use \mjolnir\base\Validator;

class ValidatorTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Validator'));
	}

	// @todo tests for \mjolnir\base\Validator

} # test

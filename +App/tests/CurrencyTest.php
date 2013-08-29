<?php namespace mjolnir\base\tests;

use \mjolnir\base\Currency;

class CurrencyTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Currency'));
	}

	// @todo tests for \mjolnir\base\Currency

} # test

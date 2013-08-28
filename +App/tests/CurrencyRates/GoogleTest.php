<?php namespace mjolnir\base\tests;

use \mjolnir\base\CurrencyRates_Google;

class CurrencyRates_GoogleTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\CurrencyRates_Google'));
	}

	// @todo tests for \mjolnir\base\CurrencyRates_Google

} # test

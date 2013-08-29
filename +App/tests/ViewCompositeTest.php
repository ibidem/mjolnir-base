<?php namespace mjolnir\base\tests;

use \mjolnir\base\ViewComposite;

class ViewCompositeTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\ViewComposite'));
	}

	// @todo tests for \mjolnir\base\ViewComposite

} # test

<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class LangTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	function tr()
	{
		// test for pass though
		$this->assertEquals('test', Lang::tr('test', null, 'doesnotexist'));
		// value tests
		Lang::lang('test');
		$this->assertEquals('success', Lang::tr('test1'));
		$this->assertEquals('success', Lang::tr('test2', array()));
	}
	
	/**
	 * @test
	 */
	function msg()
	{
		Lang::lang('test');
		$this->assertEquals('success', Lang::msg('test1'));
		$this->assertEquals('success', Lang::msg('test2', array()));
	}
	
	/**
	 * @test
	 */
	function lang()
	{
		Lang::lang('test');
		$this->assertEquals('test', Lang::get_lang());
	}
	
	/**
	 * @test
	 */
	function get_lang()
	{
		// @see lang
	}

} # test

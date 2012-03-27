<?php namespace kohana4\base;

class Trait_MetaTest_Mockup extends \app\Instantiatable
	{ use Trait_Meta; }

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Trait_MetaTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	function meta()
	{
		$test = Trait_MetaTest_Mockup::instance();
		$test->meta('test', 'success');
		$this->assertEquals('success', $test->get_meta('test'));
		$this->assertEquals('fail', $test->get_meta('doesnotexist', 'fail'));
	}
	
	/**
	 * @test
	 */
	function get_meta()
	{
		// @see meta
	}

} # test

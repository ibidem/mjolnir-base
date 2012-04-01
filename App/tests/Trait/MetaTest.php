<?php namespace ibidem\base;

class Trait_MetaTest_Mockup extends \app\Instantiatable
	{ use Trait_Meta; }

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
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

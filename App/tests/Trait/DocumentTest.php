<?php namespace ibidem\base;

class Trait_DocumentTest_Mockup extends \app\Instantiatable
	{ use Trait_Document; }

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Trait_DocumentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	function body()
	{
		$test = Trait_DocumentTest_Mockup::instance();
		$this->assertEquals('test', $test->body('test')->get_body());
		$this->assertEquals(null, $test->body(null)->get_body());
	}
	
	/**
	 * @test
	 */
	function get_body()
	{
		// @see body
	}

} # test

<?php namespace kohana4\base;

class Trait_DocumentTest_Mockup extends \app\Instantiatable
	{ use Trait_Document; }

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
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

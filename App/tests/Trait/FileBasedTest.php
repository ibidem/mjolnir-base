<?php namespace ibidem\base;

class Trait_FileBasedTest_Mockup extends \app\Instantiatable
	{ use Trait_FileBased; }

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Trait_FileBasedTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	function file()
	{
		$test = Trait_FileBasedTest_Mockup::instance();
		$test->file('tests/Trait/FileBasedTest');
		$file_path = __FILE__;
		$this->assertEquals($file_path, $test->get_file());
	}
	
	/**
	 * @test
	 * @expectedException \app\Exception_NotFound
	 */
	function file_ExceptionTest()
	{
		$test = Trait_FileBasedTest_Mockup::instance();
		$test->file('path/does/not/exist');
		$this->assertNull('should not be reached');
	}

	/**
	 * @test
	 */
	function file_path()
	{
		$test = Trait_FileBasedTest_Mockup::instance();
		$test->file_path(__FILE__);
		$file_path = __FILE__;
		$this->assertEquals($file_path, $test->get_file());
	}
	
	/**
	 * @test
	 * @expectedException \app\Exception_NotFound
	 */
	function file_path_ExceptionTest()
	{
		$test = Trait_FileBasedTest_Mockup::instance();
		$test->file_path('path/does/not/exist');
		$this->assertNull('should not be reached');
	}	
	
	/**
	 * @test
	 */
	function get_file()
	{
		// @see file & file_path
	}

} # test

<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
trait Trait_FileBased
{
	/**
	 * @var string view file
	 */
	protected $file;
		
	/**
	 * @param string file 
	 * @return $this
	 */
	public function file($file)
	{
		$file_path = \app\CFS::file('views'.DIRECTORY_SEPARATOR.$file);
		// found file?
		if ($file_path === false)
		{
			throw \app\Exception_NotFound::instance("Required file [$file] not found.");
		}
		else # found file
		{
			$this->file = $file_path;
		}
		
		return $this;
	}
	
	/**
	 * @param string explicit file path
	 * @return $this
	 */
	public function file_path($file)
	{
		$this->file = \realpath($file);
		return $this;
	}
	
	/**
	 * @return string file path
	 */
	public function get_file()
	{
		return $this->file;
	}
	
} # trait

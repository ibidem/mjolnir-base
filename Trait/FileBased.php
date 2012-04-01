<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
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
		$file_path = \app\CFS::file($file);
		// found file?
		if ($file_path === null)
		{
			throw \app\Exception_NotFound::instance
				("Required file [$file] not found.");
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
		if ( ! \file_exists($this->file))
		{
			throw \app\Exception_NotFound::instance
				("Required file [$file] not found.");
		}
		
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

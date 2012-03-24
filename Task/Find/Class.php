<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_Find_Class extends \app\Task
{
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$classfile = \str_replace('_', DIRECTORY_SEPARATOR, $this->config['class']).EXT;
		$modules = \array_keys(\app\CFS::get_modules());
		// search for class
		$files = array();
		foreach ($modules as $module)
		{
			if (\file_exists($module.DIRECTORY_SEPARATOR.$classfile))
			{
				$files[] = \str_replace(DOCROOT, '', $module.DIRECTORY_SEPARATOR.$classfile);
			}
		}
		
		if ( ! empty($files))
		{
			\sort($files);
			foreach ($files as $file)
			{
				$this->writer->status('File', $file)->eol();
			}
		}
		else # no files found
		{
			$this->writer->error('No files found.')->eol();
		}
	}
	
} # class

<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Find_Class extends \app\Task
{
	/**
	 * Execute task.
	 */
	function execute()
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

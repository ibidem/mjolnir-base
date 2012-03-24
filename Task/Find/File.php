<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_Find_File extends \app\Task
{
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$files = \app\CFS::file_list($this->config['path'], $this->config['ext']);
		if ( ! empty($files))
		{
			\sort($files);
			foreach ($files as $file)
			{
				$this->writer->status('File', \str_replace(DOCROOT, '', \realpath($file)))->eol();
			}
		}
		else # no files found
		{
			$this->writer->error('No files found.')->eol();
		}
	}
	
} # class

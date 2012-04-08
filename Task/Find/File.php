<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
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

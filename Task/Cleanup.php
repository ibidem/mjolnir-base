<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Task
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Cleanup extends \app\Task
{
	/**
	 * Execute task.
	 */
	function execute()
	{
		$this->writer->write(' Reseting cache.')->eol();
		\app\Stash::flush();
		$this->writer->write(' Removing logs.')->eol();
		\app\Filesystem::purge(APPPATH.'logs');
	}
	
} # class

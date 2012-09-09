<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Relays extends \app\Task
{
	/**
	 * Execute task.
	 */
	function execute()
	{
		$relays = \app\CFS::config('ibidem/relays');
		$found_relays = false;
		foreach ($relays as $key => $relay)
		{
			if ($relay['enabled'])
			{
				$found_relays = true;
				$this->writer->status('Route', $key)->eol();
			}
		}
		if ( ! $found_relays)
		{
			$this->writer->status('Info', 'No relays found.')->eol();
		}
	}
	
} # class
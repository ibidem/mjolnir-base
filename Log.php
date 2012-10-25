<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Log
{
	/**
	 * @param string level
	 * @param string message
	 * @param string replication path
	 * @param boolean relative path? (referring to replication)
	 */
	static function message($level, $message, $replication_path = null, $relative_path = true)
	{
		// See: cfs/+App/functions/logging
		\mjolnir\log($level, $message, $replication_path, $relative_path);
	}
	
} # class

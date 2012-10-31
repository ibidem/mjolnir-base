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
	 * Logs a message.
	 * 
	 * If a replication path is provided, the message will be logged there too.
	 * If relative path is set to false the replication path is treated as an
	 * absolute path.
	 */
	static function message($level, $message, $replication_path = null, $relative_path = true)
	{
		// See: cfs/+App/functions/logging
		\mjolnir\log($level, $message, $replication_path, $relative_path);
	}
	
} # class

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
	 * @return string
	 */
	private static function date_path()
	{
		return \date('Y').DIRECTORY_SEPARATOR.\date('m').DIRECTORY_SEPARATOR;
	}
	
	/**
	 * @param string level
	 * @param string message
	 * @param string replication path
	 * @param boolean relative path? (referring to replication)
	 */
	static function message($level, $message, $replication_path = null, $relative_path = true)
	{
		$time = \date('Y-m-d H:i:s');
		$logs_path = APPPATH.'logs'.DIRECTORY_SEPARATOR;
		$date_path = self::date_path();
		$master_logs_path = $logs_path.$date_path;
		$message = \sprintf(" %s --- %-10s | %s", $time, $level, $message);
		
		// append message to master log
		self::append_to_file($master_logs_path, \date('d').EXT, $message);
		
		if ($replication_path)
		{
			if ($relative_path)
			{
				self::append_to_file($logs_path.$replication_path.$date_path, \date('d').EXT, $message);
			}
			else # absolute path
			{
				self::append_to_file($replication_path.$date_path, \date('d').EXT, $message);
			}
		}
	}

	/**
	 * @param string path
	 * @param string file
	 * @param string message 
	 */
	private static function append_to_file($path, $file, $message)
	{		
		if ( ! \file_exists($path))
		{
			@\mkdir($path, 02777, true);
			@\chmod($path, 02777);
					
			if ( ! file_exists($path.$file))
			{
				// Create the log file
				@\file_put_contents($path.$file, PHP_EOL);
				
				// Allow anyone to write to log files
				@\chmod($path.$file, 0666);
			}
		}
		
		@\file_put_contents($path.$file, PHP_EOL.$message, FILE_APPEND);
	}
	
} # class

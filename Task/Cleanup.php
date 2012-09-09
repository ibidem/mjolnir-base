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
		$this->writer->write(' Removing logs.')->eol();
		static::empty_dir(APPPATH.'logs');
		$this->writer->write(' Reseting file cache.')->eol();
		static::empty_dir(APPPATH.'cache');
		$this->writer->write(' Removing temporary files.')->eol();
		static::remove_dir(DOCROOT.'+temp');
	}
	
	/**
	 * Empty directory.
	 */
	static function empty_dir($dir)
	{
		if (\is_dir($dir))
		{
			static::scandir($dir);
		}
	}
	
	/**
	 * Remove directory.
	 */
	static function remove_dir($dir)
	{
		if (\is_dir($dir))
		{
			static::scandir($dir);

			\rmdir($dir);
		}
	}
	
	/**
	 * Scan and unlink files.
	 */
	protected static function scandir($dir)
	{
		$files = \scandir($dir);
		foreach($files as $file)
		{
			$fullpath = $dir.'/'.$file;
			if ($file == '..' || $file == '.')
			{
				continue;
			}
			else if (\is_dir($fullpath))
			{
				static::remove_dir($fullpath);
			}
			else if (\is_file($fullpath))
			{
				\unlink($fullpath);
			}
			else # neither
			{
				throw new \app\Exception($fullpath.' is not Directory, nor File.');
			}
		}
	}
	
} # class

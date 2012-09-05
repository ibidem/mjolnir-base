<?php namespace ibidem\base;

/**
 * @package    ibidem
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
		static::remove_dir(APPPATH.'logs');
		static::remove_dir(APPPATH.'cache');
	}
	
	static function empty_dir($dir)
	{
		if (\is_dir($dir))
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
					unlink($fullpath);
				}
				else # neither
				{
					throw new \app\Exception($fullpath.' is not Directory, nor File.');
				}
			}
		}
	}
	
	/**
	 * Remove directory.
	 */
	static function remove_dir($dir)
	{
		if (\is_dir($dir))
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
					unlink($fullpath);
				}
				else # neither
				{
					throw new \app\Exception($fullpath.' is not Directory, nor File.');
				}
			}

			\rmdir($dir);
		}
	}
	
} # class

<?php namespace mjolnir\base;

/**
 * This class is used for simplifying working with files. For the most part,
 * problems related to file permissions and basic (recursive) operations.
 * 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Filesystem
{
	/**
	 * Writes contents to a file. If the path to the file doesn't exist it will
	 * automatically created; the default directory permisions will be used for
	 * directories. If you wish to determine directory permissions yourself you
	 * can create the directory before attempting to create the file.
	 *
	 * If the file already exists the permissions won't be altered.
	 *
	 * Settings permissions is not guranteed and will not generate an error.
	 *
	 * If not specified permission will be set to default.file.permissions from
	 * mjolnir/base.
	 */
	static function puts($file, $contents, $permissions = null)
	{
		if ( ! \file_exists($file))
		{
			// normalize path
			$file = \str_replace('\\', '/', $file);

			// find last directory seperator
			$last_ds_pos = \strrpos($file, '/');

			static::makedir(\substr($file, 0, $last_ds_pos));

			if ($permissions === null)
			{
				$permissions = \app\CFS::config('mjolnir/base')['default.file.permissions'];
			}

			\file_put_contents($file, $contents);

			// we attempt to set the permission
			@\chmod($file, $permissions);
		}
		else # file already exists
		{
			\file_put_contents($file, $contents);
		}
	}

	/**
	 * Creates a directory for the given path. This function is recursive and
	 * will create parent directories if required. All directories will recieve
	 * the same permission.
	 *
	 * If the directory exists nothing happens. If you wish to change the
	 * permission of a directory please simply use PHP's \chmod function
	 * directly.
	 *
	 * Note: directories require execution permission to be opened.
	 */
	static function makedir($path, $permissions = null)
	{
		// normalize path
		$path = \rtrim(\str_replace('\\', '/', $path), '/');

		// check if the directory doesn't already exist
		if ( ! \file_exists($path))
		{
			if ($permissions === null)
			{
				$permissions = \app\CFS::config('mjolnir/base')['default.dir.permissions'];
			}

			\mkdir($path, $permissions, true);
		}
	}
	
	/**
	 * Delte resource. If resource does not exist, does nothing.
	 */
	static function delete($path)
	{
		if ( ! \file_exists($path))
		{
			return; # do nothing
		}
		
		if (\is_dir($dir))
		{
			static::purge($dir);
			\rmdir($dir);
		}
		else if (\is_file($path))
		{
			\unlink($path);
		}
		else # unknown resource
		{
			throw new \Exception('Unknown Resource');
		}
	}
	
	/**
	 * Scan for files and directories and remove them. The source directory will
	 * not be removed.
	 * 
	 * Assumes provided directory is valid.
	 */
	static function purge($dir)
	{
		$files = \scandir($dir);
		foreach($files as $file)
		{
			if ($file == '..' || $file == '.')
			{
				continue;
			}
			
			$fullpath = $dir.'/'.$file;
			
			if (\is_dir($fullpath))
			{
				static::delete($fullpath);
			}
			else if (\is_file($fullpath))
			{
				\unlink($fullpath);
			}
			else # neither
			{
				throw new \app\Exception($fullpath.' is not Directory, nor a File.');
			}
		}
	}

} # class

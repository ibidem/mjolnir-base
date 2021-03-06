<?php namespace mjolnir\base;

/**
 * This class is used for simplifying working with files. For the most part,
 * problems related to file permissions and basic (recursive) operations.
 *
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
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
	 * @return string
	 */
	static function gets($file, $default = null)
	{
		if (\file_exists($file))
		{
			return \file_get_contents($file);
		}
		else # file not found
		{
			return $default;
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
	 * Delete resource. If resource does not exist, does nothing.
	 */
	static function delete($path)
	{
		if ( ! \file_exists($path))
		{
			return; # do nothing
		}

		if (\is_dir($path))
		{
			static::purge($path);
			\rmdir($path);
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
		$dir = \rtrim($dir, '/\\');
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
			else if (\is_file($fullpath) || \is_link($fullpath))
			{
				\unlink($fullpath);
			}
			else # neither
			{
				throw new \app\Exception($fullpath.' is not a Directory, nor a File.');
			}
		}
	}

	/**
	 * @return int
	 */
	static function filecount($path, $pattern, $ignore_dot_file = true, $count_dirs_as_files = false)
	{
		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			return 0;
		}

		$count = 0;
		$files = \scandir($path);
		foreach($files as $file)
		{
			if ($file == '..' || $file == '.')
			{
				continue;
			}
			else if ($ignore_dot_file && \preg_match('#^\..*#', $file))
			{
				continue;
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath) || \is_link($fullpath))
			{
				$count += static::filecount($fullpath, $pattern);
				! $count_dirs_as_files or $count += 1;
			}
			else if (\is_file($fullpath))
			{
				if (\preg_match($pattern, $file))
				{
					++$count;
				}
			}
			else # neither
			{
				\mjolnir\log('Warning', $fullpath.' is not a Directory, nor a File.');
			}
		}

		return $count;
	}

	/**
	 * @return int
	 */
	static function size($path)
	{
		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			return 0;
		}

		$size = 0;
		$files = \scandir($path);
		foreach($files as $file)
		{
			if ($file == '..' || $file == '.')
			{
				continue;
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				$size += static::size($fullpath);
			}
			else if (\is_file($fullpath) || \is_link($fullpath))
			{
				$size += \filesize($fullpath);
			}
			else # neither
			{
				\mjolnir\log('Warning', $fullpath.' is not a Directory, nor a File.');
			}
		}

		return $size;
	}

	/**
	 * @return string
	 */
	static function mimetype($path)
	{
		// @hotfix finfo doens't recognize woff files
		if (\preg_match('/.*\.woff/', $path))
		{
			return 'application/x-font-woff';
		}

		if (\defined('FILEINFO_MIME_TYPE'))
		{
			$finfo = \finfo_open(FILEINFO_MIME_TYPE);
			$mimetype = \finfo_file($finfo, $path);
			\finfo_close($finfo);

			return $mimetype;
		}
		else # not available
		{
			#
			# We are intentionally not using the system("file -i -b $path")
			# method for security reasons.
			#

			throw new \app\Exception('Missing fileinfo extention.');
		}
	}

	/**
	 * @return array
	 */
	static function files($dir, $ext = EXT, $ignore_dot_file = true)
	{
		$path = \rtrim($dir, '\\/');
		$ext = \ltrim($ext, '.');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			return [];
		}

		$result = [];
		$files = \scandir($path);
		foreach($files as $file)
		{
			if ($file == '..' || $file == '.')
			{
				continue;
			}
			else if ($ignore_dot_file && \preg_match('#^\..*#', $file))
			{
				continue;
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				$otherfiles = static::files($fullpath, $ext);
				$result = \app\Arr::merge($result, $otherfiles);
			}
			else if (\is_file($fullpath))
			{
				$fileext = \pathinfo($fullpath, PATHINFO_EXTENSION);
				if ($fileext == $ext)
				{
					$result[] = $fullpath;
				}
			}
		}

		return $result;
	}

	/**
	 * @return array
	 */
	static function matchingfiles($dir, $pattern)
	{
		$path = \rtrim($dir, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			return [];
		}

		$result = [];
		$files = \scandir($path);
		foreach($files as $file)
		{
			if (\is_file($file) && ! \preg_match($pattern, $file))
			{
				continue;
			}
			else if ($file == '..' || $file == '.')
			{
				continue;
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				$otherfiles = static::matchingfiles($fullpath, $pattern);
				$result = \app\Arr::merge($result, $otherfiles);
			}
			else
			{
				if (\preg_match($pattern, $file))
				{
					$result[] = $fullpath;
				}
			}
		}

		return $result;
	}

	/**
	 * Scan path and remove all empty directories. If a directory contains only
	 * "empty" directories it will be considered "empty".
	 */
	static function prunedirs($path)
	{
		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			return;
		}

		$files = \scandir($path);
		foreach ($files as $file)
		{
			if (\preg_match('#^[\.].*#', $file))
			{
				continue; # skip dot files
			}

			$fullpath = $path.'/'.$file;

			if (static::filecount($fullpath, '#.*$#', false, true) > 0)
			{
				static::prunedirs($fullpath);
				if (static::filecount($fullpath, '#.*$#', false, true) == 0)
				{
					\rmdir($fullpath);
				}
			}
			else # 0 files
			{
				\rmdir($fullpath);
			}
		}
	}

	/**
	 * Recursively copy a directory.
	 */
	static function copy($source, $destination)
	{
		$dir = \opendir($source);
		\app\Filesystem::makedir($destination);
		while (false !== ($file = \readdir($dir)))
		{
			if (($file != '.') && ($file != '..'))
			{
				if (\is_dir($source.'/'.$file))
				{
					static::copy($source.'/'.$file, $destination.'/'.$file);
				}
				else # file
				{
					\copy($source.'/'.$file, $destination.'/'.$file);
				}
			}
		}

		\closedir($dir);
	}

	// ------------------------------------------------------------------------
	// System file permissions helpers

	/**
	 * @return array unreadable files
	 */
	static function find_unreadable($path, $pattern)
	{
		if (empty($path))
		{
			\mjolnir\log('PHP', 'Recieved malformed variable data.');
			return []; # PHP bug; we recieved malformed data
		}

		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			throw new \Exception("The path [$path] does not exist or is not a directory.");
		}

		$unreadable = [];
		$files = \scandir($path);
		foreach ($files as $file)
		{
			if (\preg_match('#^[\.].*#', $file))
			{
				continue; # skip dot files
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				if (\is_readable($fullpath))
				{
					$unreadable = \app\Arr::merge($unreadable, static::find_unreadable($fullpath, $pattern));
				}
				else # unreadable directory
				{
					$unreadable[] = $fullpath;
				}
			}
			else # file
			{
				if (\preg_match($pattern, $file))
				{
					if ( ! \is_readable($fullpath))
					{
						$unreadable[] = $fullpath;
					}
				}
			}
		}

		return $unreadable;
	}

	/**
	 * @return array unwritable files
	 */
	static function find_unwritable($path, $pattern)
	{
		if (empty($path))
		{
			\mjolnir\log('PHP', 'Recieved malformed variable data.');
			return []; # PHP bug; we recieved malformed data
		}

		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			throw new \Exception("The path [$path] does not exist or is not a directory.");
		}

		$unwritable = [];
		$files = \scandir($path);
		foreach ($files as $file)
		{
			if (\preg_match('#^[\.].*#', $file))
			{
				continue; # skip dot files
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				if (\is_readable($fullpath) && \is_writable($fullpath))
				{
					$unwritable = \app\Arr::merge($unwritable, static::find_unwritable($fullpath, $pattern));
				}
				else # unreadable directory
				{
					// we consider it not being readable to mean it's not
					// writable since functions will fail to recursively
					// reach it
					$unwritable[] = $fullpath;
				}
			}
			else # file
			{
				if (\preg_match($pattern, $file))
				{
					if ( ! \is_writable($fullpath))
					{
						$unwritable[] = $fullpath;
					}
				}
			}
		}

		return $unwritable;
	}

	/**
	 * @return array unwritable files
	 */
	static function find_unexecutable($path, $pattern)
	{
		if (empty($path))
		{
			\mjolnir\log('PHP', 'Recieved malformed variable data.');
			return []; # PHP bug; we recieved malformed data
		}

		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			throw new \Exception("The path [$path] does not exist or is not a directory.");
		}

		$unexecutable = [];
		$files = \scandir($path);
		foreach ($files as $file)
		{
			if (\preg_match('#^[\.].*#', $file))
			{
				continue; # skip dot files
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				if (\is_readable($fullpath) && \is_executable($fullpath))
				{
					$unexecutable = \app\Arr::merge($unexecutable, static::find_unwritable($fullpath, $pattern));
				}
				else # unreadable directory
				{
					// we consider it not being readable to mean it's not
					// executable since functions will fail to recursively
					// reach it
					$unexecutable[] = $fullpath;
				}
			}
			else # file
			{
				if (\preg_match($pattern, $file))
				{
					if ( ! \is_executable($fullpath))
					{
						$unexecutable[] = $fullpath;
					}
				}
			}
		}

		return $unexecutable;
	}

	/**
	 * @return array unexecutable directories
	 */
	static function find_unexecutabledir($path)
	{
		if (empty($path))
		{
			\mjolnir\log('PHP', 'Recieved malformed variable data.');
			return []; # PHP bug; we recieved malformed data
		}

		$path = \rtrim($path, '\\/');

		if ( ! \file_exists($path) || ! \is_dir($path))
		{
			throw new \Exception("The path [$path] does not exist or is not a directory.");
		}

		$unexecutable = [];
		$files = \scandir($path);
		foreach ($files as $file)
		{
			if (\preg_match('#^[\.].*#', $file))
			{
				continue; # skip dot files
			}

			$fullpath = $path.'/'.$file;

			if (\is_dir($fullpath))
			{
				if (\is_readable($fullpath) && \is_executable($fullpath))
				{
					$unexecutable = \app\Arr::merge($unexecutable, static::find_unexecutabledir($fullpath));
				}
				else # unreadable directory
				{
					// we consider it not being readable to mean it's not
					// executable since functions will fail to recursively
					// reach it
					$unexecutable[] = $fullpath;
				}
			}
		}

		return $unexecutable;
	}

	/**
	 * This function is intendend for display.
	 *
	 * @return int|string group name or id
	 */
	static function groupname($file)
	{
		try
		{
			$gid = \filegroup($file);
			if (\function_exists('posix_getgrgid'))
			{
				return \posix_getgrgid($gid)['name'];
			}
			else # windows system, we simply return the group id
			{
				return $gid;
			}
		}
		catch (\Exception $e)
		{
			return '?';
		}
	}

	/**
	 * This function is intendend for display.
	 *
	 * @return int|string group name or id
	 */
	static function ownername($file)
	{
		try
		{
			$uid = \fileowner($file);
			if (\function_exists('posix_getpwuid'))
			{
				return \posix_getpwuid($uid)['name'];
			}
			else # windows system, we simply return the group id
			{
				return $uid;
			}
		}
		catch (\Exception $e)
		{
			return '?';
		}
	}

	/**
	 * This file returns human readable version of permissions on a file.
	 *
	 * @return string
	 */
	static function permissions($file)
	{
		try
		{
			$perms = \fileperms($file);

			if (($perms & 0xC000) == 0xC000)
			{
				// Socket
				$info = 's';
			}
			elseif (($perms & 0xA000) == 0xA000)
			{
				// Symbolic Link
				$info = 'l';
			}
			elseif (($perms & 0x8000) == 0x8000)
			{
				// Regular
				$info = '-';
			}
			elseif (($perms & 0x6000) == 0x6000)
			{
				// Block special
				$info = 'b';
			}
			elseif (($perms & 0x4000) == 0x4000)
			{
				// Directory
				$info = 'd';
			}
			elseif (($perms & 0x2000) == 0x2000)
			{
				// Character special
				$info = 'c';
			}
			elseif (($perms & 0x1000) == 0x1000)
			{
				// FIFO pipe
				$info = 'p';
			}
			else # unknown
			{
				$info = 'u';
			}

			// Owner
			$info .= (($perms & 0x0100) ? 'r' : '-');
			$info .= (($perms & 0x0080) ? 'w' : '-');
			$info .= (($perms & 0x0040) ?
					 (($perms & 0x0800) ? 's' : 'x' ) :
					 (($perms & 0x0800) ? 'S' : '-'));

			// Group
			$info .= (($perms & 0x0020) ? 'r' : '-');
			$info .= (($perms & 0x0010) ? 'w' : '-');
			$info .= (($perms & 0x0008) ?
					 (($perms & 0x0400) ? 's' : 'x' ) :
					 (($perms & 0x0400) ? 'S' : '-'));

			// World
			$info .= (($perms & 0x0004) ? 'r' : '-');
			$info .= (($perms & 0x0002) ? 'w' : '-');
			$info .= (($perms & 0x0001) ?
					 (($perms & 0x0200) ? 't' : 'x' ) :
					 (($perms & 0x0200) ? 'T' : '-'));

			return $info;
		}
		catch (\Exception $e)
		{
			return '?';
		}
	}

} # class

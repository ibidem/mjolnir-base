<?php namespace mjolnir\base;

/**
 * Whenever possible you should perform a standard debug, see http://xdebug.org/
 *
 * This class is meant for use in all the situations where you can't. Please use
 * the methods here instead of doing things like a `\var_dump` on a live site.
 *
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Debug
{
	/**
	 * Shorthand for performing dump in a path relative to the temporary file
	 * directory of the project.
	 */
	static function temp($filename, $variable, $append = false)
	{
		static::dump(\app\Env::key('tmp.path').$filename, $variable, $append);
	}

	/**
	 * Dumps a print_r to a file. You may wish to place the file in location you
	 * can view online.
	 *
	 * eg.
	 *
	 *		\app\Debug::dump('/www/debug.html', $variable);
	 *
	 * If the file contains no slash or backslash the etc.path/tmp will be used.
	 */
	static function dump($file, $variable, $append = false)
	{
		if (\is_string($variable))
		{
			$output = $variable;
		}
		else # non-string
		{
			\ob_start();
			\var_dump($variable);
			$output = \ob_get_clean();
		}

		if ( ! \preg_match('#[\\/]#', $file))
		{
			$file = \app\Env::key('tmp.path').$file;
		}

		if ($append)
		{
			\file_put_contents($file, "\n\n$output", FILE_APPEND);
		}
		else # replace contents
		{
			\file_put_contents($file, $output);
		}
	}

	/**
	 * Ouputs the debug message to the logs, a request identifier will be
	 * prepended if possible to help distinguish between different instances of
	 * the message that can occur when the piece of code is executed by various
	 * dependencies to the page (javascript, css, etc).
	 */
	static function log($message)
	{
		static $id = null;

		if ($id === null)
		{
			$id = \base_convert(\crc32(\uniqid()), 10, 32);
		}

		\mjolnir\log('Debug', "$id: $message");
	}

	/**
	 * @return string usable timestamp for debug files
	 */
	static function timestamp()
	{
		return \preg_replace('#[^0-9]#', '', \microtime(true));
	}

} # class

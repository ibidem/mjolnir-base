<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Shell
{
	/**
	 * @return boolean
	 */
	static function cmd_exists($command)
	{
		if (\strtolower(\substr(PHP_OS, 0, 3)) === 'win')
		{
			$fp = \popen("where $command", "r");
			$result = \fgets($fp, 255);
			$exists = ! \preg_match('#Could not find files#', $result);
			\pclose($fp);	
		}
		else # non-Windows
		{
			$fp = \popen("which $command", "r");
			$result = \fgets($fp, 255);
			$exists = ! empty($result);
			\pclose($fp);
		}
		
		return $exists;
	}

} # class

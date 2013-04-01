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
		$exists = false;
		if (\strtolower(\substr(PHP_OS, 0, 3)) === 'win')
		{
			$fp = \popen("where $command", "r");
			$result = \fgets($fp, 255);
			$exists = $result !== false && ! \preg_match('#Could not find files#', $result);
			\pclose($fp);
		}
		else # non-Windows
		{
			$fp = \popen("type $command >/dev/null 2>&1 || echo 'failed'", "r");
			$result = \fgets($fp, 255);
			$exists = empty($result);
			\pclose($fp);
		}

		return $exists;
	}

	/**
	 * Executes command and retrieves complete output.
	 */
	static function cmd($command)
	{
		$out = \shell_exec($command);
		return $out;
	}

} # class

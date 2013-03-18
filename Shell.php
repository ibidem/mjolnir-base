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
	
	/**
	 * Executes command and retrieves complete output.
	 */
	static function cmd($command)
	{
		$output = [];
		$return = 1;
		
		// the return value of this command is the last line
		\exec($command, $output, $return);
		
		if ($return != 0)
		{
			throw new \Exception("Failed with status [$return] => $command");
		}
		else # status: ok
		{
			return \implode("\n", $output);
		}
	}

} # class

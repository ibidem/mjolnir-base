<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Query
{
	/**
	 * @return mixed parameter or default 
	 */
	static function get($key, $default = null)
	{
		if (isset($_GET[$key]))
		{
			return $_GET[$key];
		}
		else # not set
		{
			return $default;
		}
	}
	
	/**
	 * Sets the value for a given key.
	 */
	static function set($key, $value)
	{
		$_GET[$key] = $value;
	}
	
} # class

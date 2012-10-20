<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Library
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Session
{
	/**
	 * @var \mjolnir\base\Session 
	 */
	private static $session = null;
	
	/**
	 * Starts the session.
	 */
	static function start()
	{
		if (self::$session === null)
		{
			self::$session = \app\Session_Native::instance();
		}
	}
	
	/**
	 * @return mixed parameter or default 
	 */
	static function get($key, $default = null)
	{
		if (self::$session === null)
		{
			self::$session = \app\Session_Native::instance();
		}
		
		return self::$session->get($key, $default);
	}
	
	/**
	 * @param string key
	 * @param mixed value
	 * @return mixed value
	 */
	static function set($key, $value)
	{
		if (self::$session === null)
		{
			self::$session = \app\Session_Native::instance();
		}
		
		self::$session->set($key, $value);
		
		return $value;
	}
	
	/**
	 * Destroy the session. 
	 */
	static function destroy()
	{
		if (self::$session !== null)
		{
			self::$session->destroy();
		}
	}

} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Library
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Session
{
	/**
	 * @var \ibidem\base\Session 
	 */
	private static $session = null;
	
	/**
	 * @return mixed parameter or default 
	 */
	public static function get($key, $default = null)
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
	 */
	public static function set($key, $value)
	{
		if (self::$session === null)
		{
			self::$session = \app\Session_Native::instance();
		}
		
		self::$session->set($key, $value);
	}
	
	/**
	 * Destroy the session. 
	 */
	public static function destroy()
	{
		if (self::$session === null)
		{
			self::$session = \app\Session_Native::instance();
		}
		
		self::$session->destroy();
	}

} # class

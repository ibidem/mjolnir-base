<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Relay
{
	/**
	 * @param string key
	 * @param function callback
	 * @param \ibidem\types\Matcher
	 */
	public static function process($key, $callback, $matcher = null)
	{
		$relays = \app\CFS::config('ibidem/relays');
		if (isset($relays[$key]) && $relays[$key]['enabled'])
		{
			if (isset($relays[$key]['route']))
			{
				$matcher = $relays[$key]['route'];
			}
			else if ($matcher === null)
			{
				// no matcher provided; fail relay
				return;
			}
			
			$matcher->relay_config($relays[$key]);
			if ($matcher->check())
			{
				$callback($relays[$key], $key);
				exit;
			}
		}
	}
	
	/**
	 * Checks all relays. When a relay is hit the function may stop.
	 * 
	 * @return boolean success?
	 */
	public static function check_all($relay_file = 'relays', $ext = EXT)
	{
		$relay_file = $relay_file.$ext;
		$paths = \app\CFS::paths();
		foreach ($paths as $path)
		{
			if (\file_exists($path.$relay_file))
			{
				include $path.$relay_file;
			}
		}
		
		// not really required, if execution passes past this method then 
		// routing should have failed and a there should be a 404 error thrown
		// how it is possible the route handling has been altered and doesn't 
		// act like a switch, in which case this statement should correctly 
		// send the status of the function
		return false;
	}
	
	/**
	 * @param string route key or alias
	 * @return mixed
	 */
	public static function route($key)
	{
		$relays = \app\CFS::config('ibidem/relays');
		if (isset($relays[$key]))
		{
			return $relays[$key]['route'];
		}
		else # not in relays, check application aliases 
		{
			$aliases = \app\CFS::config('aliases');
			if (isset($aliases['route'][$key]))
			{
				if (isset($relays[$aliases['route'][$key]]))
				{
					return $relays[$aliases['route'][$key]]['route'];
				}
				else # invalid alias
				{
					throw new \app\Exception
						('Invalid value ['.$aliases['route'][$key].'] for alias ['.$key.']');
				}
			}
		}
		
		return null;
	}
	
} # class

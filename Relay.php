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
	static function process($key, $callback, $matcher = null)
	{
		$relays = \app\CFS::config('ibidem/relays');
		if (isset($relays[$key]))
		{
			// if enabled is not provided we assume true
			if ( ! isset($relays[$key]['enabled']) || $relays[$key]['enabled'])
			{
				if (isset($relays[$key]['matcher']))
				{
					$matcher = $relays[$key]['matcher'];
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
	}
	
	/**
	 * Checks all relays. When a relay is hit the function may stop.
	 * 
	 * @return boolean success?
	 */
	static function check_all($relay_file = 'relays', $ext = EXT)
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
	 * @return \ibidem\types\URLCompatible
	 */
	static function matcher($key)
	{
		$relays = \app\CFS::config('ibidem/relays');
		if (isset($relays[$key]))
		{
			return $relays[$key]['matcher'];
		}
		else # not in relays, check application aliases 
		{
			$aliases = \app\CFS::config('aliases');
			if (isset($aliases['relay'][$key]))
			{
				if (isset($relays[$aliases['relay'][$key]]))
				{
					return $relays[$aliases['relay'][$key]]['matcher'];
				}
				else # invalid alias
				{
					throw new \app\Exception
						('Invalid value ['.$aliases['relay'][$key].'] for alias ['.$key.']');
				}
			}
		}
		
		return null;
	}
	
} # class

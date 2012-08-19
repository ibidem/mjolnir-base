<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Html
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class URL
{
	/**
	 * @return \ibidem\types\URLCompatible
	 */
	static function route($key)
	{
		// search routes
		$pattern = \app\Route::matcher($key);
		
		if ($pattern === null)
		{
			// search relays
			$pattern = \app\Relay::matcher($key);
		}
		
		return $pattern;
	}

	/**
	 * @return string url
	 */
	static function href($key, array $params = null, $query = null, $protocol = 'http')
	{
		$pattern = static::route($key);
		if ($pattern === null)
		{
			throw new \app\Exception_NotApplicable('No pattern for '.$key);
		}
		else # got pattern
		{
			return static::route($key)->url($params, $protocol).$query;
		}
	}
	
} # class
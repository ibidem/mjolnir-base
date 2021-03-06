<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class URL
{
	/**
	 * @return \mjolnir\types\URLCompatible
	 */
	static function route($key)
	{
		// search routes
		$pattern = \app\Router::route($key);

		if ($pattern === null)
		{
			// search relays
			$pattern = \app\Router::relay($key);
		}

		return $pattern;
	}

	/**
	 * @return string url
	 */
	static function href($key, array $params = null, array $query = null, $protocol = null)
	{
		$pattern = static::route($key);

		if ($pattern === null)
		{
			throw new \app\Exception('No pattern for '.$key);
		}
		else # got pattern
		{
			return $pattern->url($params, $query, $protocol);
		}
	}

	/**
	 * @return string
	 */
	static function base()
	{
		$base = \app\CFS::config('mjolnir/base');
		return $base['protocol'].$base['domain'].$base['path'];
	}

} # class

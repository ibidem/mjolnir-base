<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cookie
{
	/**
	 * Sets a cookie. Cookies are signed.
	 */
	static function set($key, $value, $expires = null, $secure = false)
	{
		static $cookie_session = null;

		if ($expires === null)
		{
			$expires = \app\CFS::config('mjolnir/cookies')['default.expires'];
		}

		if ($cookie_session === null)
		{
			$cookie_session = static::cookie_session();
		}

		$cookie_value = \hash_hmac(\app\CFS::config('mjolnir/cookies')['algorythm'], $value, $cookie_session).'-'.$value;

		$base_config = \app\CFS::config('mjolnir/base');
		\setcookie($key, $cookie_value, \time() + $expires, $base_config['path'], $base_config['domain'], $secure);
	}

	/**
	 * @return string cookie value or default
	 */
	static function get($key, $default = null)
	{
		static $cookie_session = null;

		if ( ! isset($_COOKIE[$key]))
		{
			return $default;
		}

		if ($cookie_session === null)
		{
			$cookie_session = static::cookie_session();
		}

		$offset = \strpos($_COOKIE[$key], '-');

		if ($offset === false)
		{
			return $default;
		}

		$value = \substr($_COOKIE[$key], $offset + 1);
		$signature = \substr($_COOKIE[$key], 0, $offset);

		if ($signature === \hash_hmac(\app\CFS::config('mjolnir/cookies')['algorythm'], $value, $cookie_session))
		{
			return $value;
		}
		else # failed signature test
		{
			return $default;
		}
	}

	/**
	 * Deletes cookie. The secure paramter must be provided in the case of
	 * cookies that used it.
	 */
	static function delete($key)
	{
		$base_config = \app\CFS::config('mjolnir/base');
		\setcookie($key, '0', time() - 3600, $base_config['path'], $base_config['domain'], false);
		\setcookie($key, '0', time() - 3600, $base_config['path'], $base_config['domain'], true);
	}

	/**
	 * Creates a cookie session using the user's credentials and the secret key.
	 *
	 * @return string session key
	 */
	protected static function cookie_session()
	{
		$cookie_key = \app\CFS::config('mjolnir/cookies')['key'];

		if ($cookie_key === null)
		{
			if ( ! \app\CFS::config('mjolnir/base')['development'])
			{
				throw new \app\Exception
					('Internal security process has failed. Cause: corrupt configuration.');
			}
			else # development mode
			{
				throw new \app\Exception
					('Cookie security key is not set.');
			}
		}

		return $cookie_key;
	}

} # class

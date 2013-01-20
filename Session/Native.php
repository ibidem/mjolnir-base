<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Session
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Session_Native extends \app\Instantiatable implements \mjolnir\types\Meta
{
	use \app\Trait_Meta;

	/**
	 * @var boolean
	 */
	private $destroyed = false;

	/**
	 * @return \mjolnir\base\Session_Native
	 */
	static function instance()
	{
		static $instance = null;

		// session exists?
		if ($instance === null)
		{
			$instance = parent::instance();

			// load session configuration
			$session_config = \app\CFS::config('mjolnir/sessions');
			$base_config = \app\CFS::config('mjolnir/base');
			\session_set_cookie_params
				(
					$session_config['lifetime'], # lifetime (seconds)
					$base_config['path'],        # path
					$base_config['domain'],      # domain
					$session_config['secure'],   # secure
					$session_config['httponly']  # httponly
				);

			// start session
			\session_start();

			// write the session at shutdown
			\register_shutdown_function(array($instance, 'close'));
		}

		return $instance;
	}

	/**
	 * @return boolean
	 */
	function close()
	{
		if (\headers_sent() || $this->destroyed)
		{
			return false;
		}

		try
		{
			\session_write_close();
			return true;
		}
		catch (\Exception $e)
		{
			\mjolnir\log_exception($e);
			return false;
		}
	}

	/**
	 * @return boolean status
	 */
	function destroy()
	{
		if ($this->destroyed)
		{
			return true;
		}

		// destroy the current session
		\session_destroy();

		$this->destroyed = true;

		return true;
	}

	/**
	 * @return int id
	 */
	function regenerate()
	{
		// regenerate the session id
		\session_regenerate_id();
		return \session_id();
	}

	/**
	 * @return mixed parameter or default
	 */
	function get($key, $default = null)
	{
		return \array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
	}

	/**
	 * @param string key
	 * @param mixed value
	 * @return \mjolnir\types\Params $this
	 */
	function set($key, $value)
	{
		$_SESSION[$key] = $value;

		return $this;
	}

	/**
	 * @param array associative array of key values
	 * @return \mjolnir\types\Params $this
	 */
	function populate_params(array $params)
	{
		$_SESSION = $params;

		return $this;
	}

	/**
	 * @return array
	 */
	function to_array()
	{
		return $_SESSION;
	}

} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Session
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Session_Native extends \app\Instantiatable
	implements \ibidem\types\Params
{
	/**
	 * @var boolean 
	 */
	private $destroyed = false;
	
	/**
	 * @return \ibidem\base\Session_Native 
	 */
	public static function instance()
	{
		static $instance = null; 
		
		// session exists?
		if ($instance === null)
		{
			$instance = parent::instance();
			
			// load session configuration
			$session_config = \app\CFS::config('ibidem/sessions');
			$base_config = \app\CFS::config('ibidem/base');
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
	public function close()
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
			\app\Log::message
				(
					'ERROR', 
					$e->getMessage().' - '.$e->getLine().' '.$e->getFile()
				);
			
			return false;
		}
	}
	
	/**
	 * @return boolean status 
	 */
	public function destroy()
	{
		// destroy the current session
		\session_destroy();
		
		$this->destroyed = true;

		return true;
	}

	/**
	 * @return int id 
	 */
	public function regenerate()
	{
		// regenerate the session id
		\session_regenerate_id();
		return \session_id();
	}
	
	/**
	 * @return mixed parameter or default 
	 */
	public function get($key, $default = null)
	{
		return \array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
	}
	
	/**
	 * @param string key
	 * @param mixed value
	 * @return \ibidem\types\Params $this
	 */
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
		
		return $this;
	}
	
	/**
	 * @param array associative array of key values
	 * @return \ibidem\types\Params $this
	 */
	public function populate_params(array $params)
	{
		$_SESSION = $params;
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function to_array()
	{
		return $_SESSION;
	}

} # class

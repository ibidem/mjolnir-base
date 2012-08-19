<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
trait Trait_Params
{
	/**
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * @return mixed parameter or default 
	 */
	function get($key, $default = null) 			
	{
		return isset($this->params[$key]) ? $this->params[$key] : $default;
	}

	/**
	 * @param array associative array of key values
	 * @return \ibidem\base\Params $this
	 */
	function populate_params(array $params) 
	{
		foreach ($params as $key => $param)
		{
			$this->params[$key] = $param;
		}
		
		return $this;
	}
	
	/**
	 * @return array 
	 */
	function to_array()
	{
		return $this->params;
	}

	/**
	 * @param string key
	 * @param mixed value
	 * @return \ibidem\base\Params $this
	 */
	function set($key, $value) 
	{
		$this->params[$key] = $value;
		
		return $this;
	}
	
} # trait
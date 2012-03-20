<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Params extends \app\Instantiatable 
	implements \kohana4\types\Params
{
	/**
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * @return mixed parameter or default 
	 */
	public function get($key, $default = null) 			
	{
		return isset($this->params[$key]) ? $this->params[$key] : $default;
	}

	/**
	 * @param array associative array of key values
	 * @return \kohana4\types\Params
	 */
	public function populate_params(array $params) 
	{
		foreach ($params as $key => $param)
		{
			$this->params[$key] = $param;
		}
	}

	/**
	 * @param string key
	 * @param mixed value
	 * @return \kohana4\types\Params
	 */
	public function set($key, $value) 
	{
		$this->params[$key] = $value;
		
		return $this;
	}
	
} # class

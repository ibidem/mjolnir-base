<?php namespace kohana4\base;

/** 
 * Very simple Regex route. Simply matches to pattern.
 * 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Route_Regex extends \app\Instantiatable 
	implements 
		\kohana4\types\Matcher, 
		\kohana4\types\RelayCompatible,
		\kohana4\types\Parameterized
{
	/**
	 * @var string
	 */
	protected $regex;
	
	/**
	 * @param string $regex
	 * @return \kohana4\base\Route_Regex
	 */
	public static function instance($regex = '+.*+')
	{
		$instance = parent::instance();
		$instance->pattern($regex);
		return $instance;
	}
	
	/**
	 * Pattern to match.
	 */
	function pattern($regex)
	{
		$this->regex = $regex;
		return $this;
	}

	/**
	 * @return boolean defined route matches? 
	 */
	public function check() 
	{
		$uri = \app\Layer_HTTP::detect_uri();
		if ($uri)
		{		
			return \preg_match($this->regex, $uri);
		}
		
		return false;
	}

	/**
	 * @param array relay configuration
	 * @return $this
	 */
	public function relay_config(array $relay)
	{		
		return $this;
	}
	
	/**
	 * @return \kohana4\types\Params
	 */
	public function get_params()
	{
		return \app\Params::instance();
	}
	
} # class

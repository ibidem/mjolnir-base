<?php namespace ibidem\base;

/** 
 * Very simple Regex route. Simply matches to pattern.
 * 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Route_Regex extends \app\Instantiatable 
	implements 
		\ibidem\types\Matcher, 
		\ibidem\types\RelayCompatible,
		\ibidem\types\Parameterized,
		\ibidem\types\Contextual
{
	/**
	 * @var string
	 */
	protected $regex;
	
	/**
	 * @var string
	 */
	protected $uri;
	
	/**
	 * @param string $regex
	 * @return \ibidem\base\Route_Regex
	 */
	static function instance($uri = null)
	{
		$instance = parent::instance();
		
		if ($uri)
		{
			$instance->uri = $uri;
		}
		else # no url
		{
			$instance->uri = Layer_HTTP::detect_uri();
		}
		
		return $instance;
	}
	
	/**
	 * Pattern to match.
	 * 
	 * @return \ibidem\base\Route_Regex $this
	 */
	function regex($regex)
	{
		$this->regex = $regex;
		return $this;
	}

	/**
	 * @return boolean defined route matches? 
	 */
	function check() 
	{		
		if ($this->uri !== null)
		{
			return \preg_match($this->regex, $this->uri);
		}
		
		return false;
	}

	/**
	 * @param array relay configuration
	 * @return \ibidem\base\Route_Regex $this
	 */
	function relay_config(array $relay)
	{		
		return $this;
	}
	
	/**
	 * @return \ibidem\types\Params
	 */
	function get_params()
	{
		return \app\Params::instance();
	}
	
	/**
	 * @return array context information 
	 */
	function get_context()
	{
		// regex route is just a fancier path route, and has no context to it
		return array();
	}
	
} # class

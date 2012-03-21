<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Route_Path extends \app\Instantiatable 
	implements 
		\kohana4\types\Matcher, 
		\kohana4\types\RelayCompatible,
		\kohana4\types\Parameterized,
		\kohana4\types\URLCompatible
{
	/**
	 * @var string
	 */
	protected $path;
	
	/**
	 * @var string
	 */
	protected $url_base;
	
	/**
	 * @var \kohana4\types\Params
	 */
	protected $params;
	
	/**
	 * @param string $regex
	 * @return \kohana4\base\Route_Path
	 */
	public static function instance($uri = null)
	{
		$instance = parent::instance();
		
		if ($uri)
		{
			$instance->uri = $uri;
		}
		else # no uri
		{
			$instance->uri = Layer_HTTP::detect_uri();
		}
		
		// setup params
		$instance->params = \app\Params::instance();
		
		return $instance;
	}
	
	/**
	 * Pattern to match.
	 */
	public function path($path)
	{
		$this->path = \trim($path, '/');
		return $this;
	}

	/**
	 * @return boolean defined route matches? 
	 */
	public function check() 
	{		
		if ($this->uri !== null)
		{
			return $this->path === \trim($this->uri, '/');
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
		return $this->params;
	}
	
	/**
	 * By passing a null protocol the function should return a protocol-less URL
	 * so that the protocol can be determined in context. 
	 * 
	 * See: "5. Relative URI References" of http://www.ietf.org/rfc/rfc2396.txt
	 * 
	 * "Why relative?" For one URL's might are not always for http, so avoiding 
	 * the assumtion is a plus in and of itself. But basically one problem it 
	 * solves is the need to programitically coerce every single link or URL 
	 * passed into a document to the correct version. So for example consider 
	 * http and https. Passing from http to https and vice versa is not 
	 * something you should be doing too often as it may have adverse effects on 
	 * the user's clients (notices, etc). This is why you'll see sites just go 
	 * full out https, since the overhead these days is relatively non-existent. 
	 * 
	 * By saying...
	 * 
	 *     //example.com/something
	 * 
	 * ...it will magically translate to https://example.com/something or 
	 * http://example.com/something on the client side. Note that it's not 
	 * necesarly as straight forward as "the document's protocol". Please read 
	 * the rfc2396 (full link above) for more information.
	 * 
	 * @param array list of paramters
	 * @param string protocol
	 * @return string
	 */
	public function url(array $params = array(), $protocol = null)
	{
		return
			// relative protocol?
			($protocol === null ? '//' : $protocol.'://').
			// url_base is set?
			($this->url_base ? $this->url_base : \app\CFS::config('kohana4/base')['url_base']).
			// append the uri
			'/'.$this->path;		
	}
	
	/**
	 * @param array list of paramters
	 * @param string protocol
	 * @return string
	 */
	public function canonical_url(array $params, $protocol)
	{
		return $this->url($params, $protocol);
	}
	
	/**
	 * Base for the url, if not defined should retrieve kohana4/base value.
	 * 
	 * @param string url base
	 * @return $this
	 */
	public function url_base($url_base = null)
	{
		$this->url_base = $url_base;
		return $this;
	}
	
} # class

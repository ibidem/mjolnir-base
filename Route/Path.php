<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Route_Path extends \app\Instantiatable 
	implements 
		\ibidem\types\Matcher, 
		\ibidem\types\RelayCompatible,
		\ibidem\types\Parameterized,
		\ibidem\types\URLCompatible,
		\ibidem\types\Contextual
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
	 * @var \ibidem\types\Params
	 */
	protected $params;
	
	/**
	 * @param string regex
	 * @return \ibidem\base\Route_Path $this
	 */
	static function instance($uri = null)
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
	 * 
	 * @return \ibidem\base\Route_Path $this
	 */
	function path($path)
	{
		$this->path = \trim($path, '/');
		return $this;
	}

	/**
	 * @return boolean defined route matches? 
	 */
	function check() 
	{		
		if ($this->uri !== null)
		{
			return $this->path === \trim($this->uri, '/');
		}
		
		return false;
	}

	/**
	 * @param array relay configuration
	 * @return \ibidem\base\Layer_Path $this
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
	function url(array $params = array(), $protocol = null)
	{
		// relative protocol?
		$url = ($protocol === null ? '//' : $protocol.'://'); 
		// url_base is set?
		if ($this->url_base)
		{
			$url .= $this->url_base;
		}
		else # no url base set
		{
			$base = \app\CFS::config('ibidem/base');
			$url .= $base['domain'].$base['path'];
		}
		
		// append the uri
		return $url.$this->path;		
	}
	
	/**
	 * @param array list of paramters
	 * @param string protocol
	 * @return string
	 */
	function canonical_url(array $params, $protocol)
	{
		return $this->url($params, $protocol);
	}
	
	/**
	 * Base for the url, if not defined should retrieve ibidem/base value.
	 * 
	 * @param string url base
	 * @return \ibidem\base\Layer_Path $this
	 */
	function url_base($url_base = null)
	{
		$this->url_base = $url_base;
		return $this;
	}
	
	/**
	 * @return array context information 
	 */
	function get_context()
	{
		// path route has no context of it's own
		return array();
	}
	
} # class

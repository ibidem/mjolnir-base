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
	 * @param string $regex
	 * @return \kohana4\base\Route_Regex
	 */
	public static function instance($path = '')
	{
		$instance = parent::instance();
		$instance->pattern($path);
		return $instance;
	}
	
	/**
	 * Pattern to match.
	 */
	function pattern($path)
	{
		$this->path = \trim($path, '/');
		return $this;
	}

	/**
	 * @return boolean defined route matches? 
	 */
	public function check() 
	{		
		$uri = \app\Layer_HTTP::detect_uri();
		if ($uri !== null)
		{
			return $this->path === \trim($uri, '/');
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
		$kohana_base = \app\CFS::config('kohana4/base');
		$protocol = $protocol === null ? '//' : $protocol.'://';
		return $protocol.\rtrim($kohana_base['url_base'], '/').'/'.$this->path;
		
	}
	
	/**
	 * @param array list of paramters
	 * @param string protocol
	 * @return string
	 */
	public function canonical_url(array $params, $protocol)
	{
		return $this->url();
	}
	
} # class

<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Route_Pattern extends \app\Instantiatable
	implements
		\mjolnir\types\Matcher,
		\mjolnir\types\RelayCompatible,
		\mjolnir\types\Parameterized,
		\mjolnir\types\URLCompatible,
		\mjolnir\types\Contextual
{
	// Defines the pattern of a <segment>
	protected static $REGEX_KEY     = '<([a-zA-Z0-9_]++)>';

	// What can be part of a <segment> value
	protected static $REGEX_SEGMENT = '[^/.,;?\n]++'; # all except non url chars

	// What must be escaped in the route regex
	protected static $REGEX_ESCAPE  = '[.\\+*?[^\\]${}=!|]';

	/**
	 * @var string
	 */
	protected $matched_pattern;

	/**
	 * @var string
	 */
	protected $standard_pattern;

	/**
	 * @var string
	 */
	protected $standard_uri;

	/**
	 * @var string
	 */
	protected $canonical_pattern;

	/**
	 * @var string
	 */
	protected $canonical_uri;

	/**
	 * @var \mjolnir\types\Params
	 */
	protected $params;

	/**
	 * @var string
	 */
	protected $url_base;

	/**
	 * @var string
	 */
	protected $uri;

	/**
	 * @param string $regex
	 * @return \mjolnir\base\Route_Pattern
	 */
	static function instance($the_uri = null)
	{
		$instance = parent::instance();

		if ($the_uri)
 		{
			$instance->uri = $the_uri;
		}
		else # no uri
		{
			$instance->uri = \trim(\app\Layer_HTTP::detect_uri(), '/');
		}

		// setup params
		$instance->params = \app\Params::instance();

		return $instance;
	}

	/**
	 * @param string pattern
	 * @param array regex
	 * @return \mjolnir\base\Route_Pattern $this
	 */
	function canonical($pattern, array $regex)
	{
		$this->canonical_uri = $pattern;
		$this->canonical_pattern = static::setup_pattern($pattern, $regex);

		return $this;
	}

	/**
	 * @param string pattern
	 * @param array regex
	 * @return \mjolnir\base\Route_Pattern $this
	 */
	function standard($pattern, array $regex)
	{
		$this->standard_uri = $pattern;
		$this->standard_pattern = static::setup_pattern($pattern, $regex);

		return $this;
	}

	/**
	 * @param string uri
	 * @param array regex
	 * @return \mjolnir\base\Route_Pattern $this
	 */
	protected static function setup_pattern($uri, array $regex)
	{
		// the URI should be considered literal except for keys and optional parts
		// escape everything preg_quote would escape except for : ( ) < >
		$expression = \preg_replace('#'.static::$REGEX_ESCAPE.'#', '\\\\$0', $uri);

		if (\strpos($expression, '(') !== false)
		{
			// make optional parts of the URI non-capturing and optional
			$expression = \str_replace
				(
					array('(', ')'),
					array('(?:', ')?'),
					$expression
				);
		}

		// insert default regex for keys
		$expression = \str_replace
			(
				array('<', '>'),
				// named subpattern PHP4.3 compatible: (?P<key>regex)
				// http://php.net/manual/en/function.preg-match.php#example-4371
				array('(?P<', '>'.static::$REGEX_SEGMENT.')'),
				$expression
			);

		$search = $replace = array();
		foreach ($regex as $key => $value)
		{
			$search[]  = "<$key>".static::$REGEX_SEGMENT;
			$replace[] = "<$key>$value";
		}

		// replace the default regex with the user-specified regex
		$expression = \str_replace($search, $replace, $expression);

		return '#^'.$expression.'$#uD';
	}

	/**
	 * @param string uri
	 * @param string regex pattern
	 * @param \mjolnir\types\Params
	 * @return \mjolnir\types\Params
	 */
	protected static function match_params($uri, $pattern, $params)
	{
		if ( ! \preg_match($pattern, $uri, $matches))
			return null;

		foreach ($matches as $key => $value)
		{
			if (\is_int($key))
			{
				// skip all unnamed keys
				continue;
			}

			// set the value for all matched keys
			$params->set($key, $value);
		}

		return $params;
	}

	/**
	 * @return boolean defined route matches?
	 */
	function check()
	{
		if ($this->canonical_pattern !== null && \preg_match($this->canonical_pattern, $this->uri))
		{
			$this->matched_pattern =& $this->canonical_pattern;
			return true;
		}

		if ($this->standard_pattern !== null && \preg_match($this->standard_pattern, $this->uri))
		{
			$this->matched_pattern =& $this->standard_pattern;
			return true;
		}

		// no match
		return false;
	}

	/**
	 * @param string uri regex pattern
	 * @param array uri parameters
	 * @return string
	 */
	protected static function generate_uri($uri, array $params = null)
	{
		if (\strpos($uri, '<') === false && \strpos($uri, '(') === false)
		{
			// this is a static route, no need to replace anything
			return $uri;
		}

		// cycle though all optional groups
		while (\preg_match('#\([^()]++\)#', $uri, $match)) # match is populated
		{
			// search for the matched value
			$search = $match[0];

			// remove the parenthesis from the match as the replace
			$replace = \substr($match[0], 1, -1);

			while (\preg_match('#'.static::$REGEX_KEY.'#', $replace, $match))
			{
				list($key, $param) = $match;

				if (isset($params[$param]))
				{
					// replace the key with the parameter value
					$replace = \str_replace($key, $params[$param], $replace);
				}
				else # don't have paramter
				{
					// this group has missing parameters
					$replace = '';
					break;
				}
			}

			// replace the group in the URI
			$uri = \str_replace($search, $replace, $uri);
		}

		// cycle though required paramters
		while (\preg_match('#'.static::$REGEX_KEY.'#', $uri, $match))
		{
			list($key, $param) = $match;

			if ( ! isset($params[$param]))
			{
				$params_err = array();
				foreach ($params as $key => $value)
				{
					$params_err[] = "$key => $value";
				}

				$uri = \htmlspecialchars($uri);
				$params_err = \implode(', ', $params_err);
				throw new \app\Exception_NotApplicable
					("Required route parameter [$param] not passed when trying to generate uri [$uri] with params [$params_err]");
			}
			else # paramter is set
			{
				$uri = \str_replace($key, $params[$param], $uri);
			}
		}

		// trim all extra slashes from the URI
		return \preg_replace('#//+#', '/', \rtrim($uri, '/'));
	}

	/**
	 * @param array relay configuration
	 * @return \mjolnir\base\Route_Pattern $this
	 */
	function relay_config(array $relay)
	{
		return $this;
	}

	/**
	 * @return \mjolnir\types\Params
	 */
	function get_params()
	{
		if ($this->matched_pattern === null)
		{
			$this->check();
		}

		return static::match_params
			(
				$this->uri,
				$this->matched_pattern,
				$this->params
			);
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
	function url(array $params = null, $protocol = null)
	{
		if ($params == null)
		{
			$params = array();
		}
		if ($this->standard_pattern)
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
				$base = \app\CFS::config('mjolnir/base');
				$url .= $base['domain'].$base['path'];
			}

			// append the uri
			return $url.static::generate_uri($this->standard_uri, $params);
		}
		else if ($this->canonical_pattern && $protocol)
		{
			return $this->canonical_url($params, $protocol);
		}
		else # missing protocol; can't use canonical
		{
			throw new \app\Exception_NotApplicable
				('Missing protocol; can not generate URL.');
		}

		return null;
	}

	/**
	 * @param array list of paramters
	 * @param string protocol
	 * @return string
	 */
	function canonical_url(array $params, $protocol)
	{
		if ($this->canonical_pattern)
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
				$base = \app\CFS::config('mjolnir/base');
				$url .= $base['domain'].$base['path'];
			}

			// append the uri
			return $url.static::generate_uri($this->canonical_uri, $params);
		}
		else if ($this->standard_pattern)
		{
			return $this->url($params, $protocol);
		}

		return null;
	}

	/**
	 * Base for the url, if not defined should retrieve mjolnir/base value.
	 *
	 * @param string url base
	 * @return \mjolnir\base\Route_Pattern $this
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
		return $this->params->to_array();
	}

} # class

<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_HTTP extends \app\Layer 
	implements 
		\ibidem\types\Params, 
		\ibidem\types\HTTP
{
	use \app\Trait_Params;
	
	/**
	 * @var string
	 */
	protected static $layer_name = \ibidem\types\HTTP::LAYER_NAME;
	
	/**
	 * @var string
	 */
	protected $status;
	
	/**
	 * @return \ibidem\base\Layer_HTTP
	 */
	static function instance()
	{
		$instance = parent::instance();
		$http = \app\CFS::config('ibidem/http');
		$instance->params = $http['meta'];
		$instance->status = $http['status'];
		
		\app\GlobalEvent::listener('http:status', function ($status) use ($instance) {
			$instance->status($status);
		});
		
		\app\GlobalEvent::listener('http:attributes', function ($params) use ($instance) {
			foreach ($params as $key => $value)
			{
				$instance->set($key, $value);
			}
		});
		
		\app\GlobalEvent::listener('http:content-type', function ($expires) use ($instance) {
			$instance->content_type($expires);
		});
		
		\app\GlobalEvent::listener('http:expires', function ($expiration_time) use ($instance) {
			$expires = $expiration_time - \time();
			$instance->set('Pragma', 'public');
			$instance->set('Cache-Control', 'maxage='.$expires);
			$instance->set
				(
					'Expires', 
					\gmdate('D, d M Y H:i:s', \time() + $expires).' GMT'
				);
		});
		
		\app\GlobalEvent::listener('http:send-headers', function ($params) use ($instance) {
			$instance->headerinfo();
		});
		
		return $instance;
	}	
	
	/**
	 * @return string location identifier (null if not found)
	 */
	static function detect_uri()
	{
		static $detected_uri = null;
		
		// did we do this already?
		if (isset($detected_uri))
		{
			return $detected_uri;
		}
		
		if ( ! empty($_SERVER['PATH_INFO']))
		{
			// PATH_INFO does not contain the docroot or index
			$uri = $_SERVER['PATH_INFO'];
		}
		else # empty PATH_INFO
		{
			// REQUEST_URI and PHP_SELF include the docroot and index
			if (isset($_SERVER['REQUEST_URI']))
			{
				/**
				 * We use REQUEST_URI as the fallback value. The reason
				 * for this is we might have a malformed URL such as:
				 *
				 *  http://localhost/http://example.com/judge.php
				 *
				 * which parse_url can't handle. So rather than leave empty
				 * handed, we'll use this.
				 */
				$uri = $_SERVER['REQUEST_URI'];

				if ($request_uri = \parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
				{
					// Valid URL path found, set it.
					$uri = $request_uri;
				}

				// Decode the request URI
				$uri = \rawurldecode($uri);
			}
			elseif (isset($_SERVER['PHP_SELF']))
			{
				$uri = $_SERVER['PHP_SELF'];
			}
			elseif (isset($_SERVER['REDIRECT_URL']))
			{
				$uri = $_SERVER['REDIRECT_URL'];
			}
			else # failed to detect
			{
				return $detected_uri = null;
			}

			$config = \app\CFS::config('ibidem/base');
			
			// get the path from the base URL
			$url_base = \parse_url($config['domain'].$config['path'], PHP_URL_PATH);

			if (\strpos($uri, $url_base) === 0)
			{
				// remove the base URL from the URI
				$uri = (string) \substr($uri, \strlen($url_base));
			}
		}
		
		// remove path
		$base_config = \app\CFS::config('ibidem/base');
		if (\substr($uri, 0, \strlen($base_config['path']) ) == $base_config['path']) 
		{
			if (\strlen($base_config['path']) == \strlen($uri))
			{
				$uri = '';
			}
			else # uri is larger
			{
				$uri = \substr($uri, \strlen($base_config['path']), \strlen($uri));
			}
			
		}
		
		return $detected_uri = $uri;
	}
	
	/**
	 * Executes non-content related tasks before main contents.
	 */
	function headerinfo()
	{
		\header($this->status);
		// process meta
		foreach ($this->params as $key => $value)
		{
			\header("$key: $value");
		}
	}	
	
	/**
	 * Execute the layer.
	 */
	function execute()
	{
		try
		{
			parent::execute();
			// got sublayer?
			if ($this->layer)
			{
				// it is possible this is used higher up
				$this->contents($this->layer->get_contents());
			}
		}
		catch (\Exception $exception)
		{
			$this->exception($exception, true);
		}
	}

	/**
	 * @param \Exception exception
	 * @return boolean is NotFound exception?
	 */
	private function is_notfound_exception(\Exception $exception)
	{
		return \is_a($exception, '\\ibidem\\types\\Exception') 
			&& $exception->get_type() === \ibidem\types\Exception::NotFound;
	}
	
	
	/**
	 * Fills body and approprite calls for current layer, and passes the 
	 * exception up to be processed by the layer above, if the layer has a 
	 * parent.
	 * 
	 * @param \Exception
	 * @param boolean layer is origin of exception?
	 */
	function exception(\Exception $exception, $no_throw = false, $origin = false)
	{
		if (self::is_notfound_exception($exception))
		{
			$this->status(\ibidem\types\HTTP::STATUS_NotFound);
		}
		else # some error we don't know about
		{
			$this->status(\ibidem\types\HTTP::STATUS_InternalServerError);
		}
		
		// default execution from Layer
		parent::exception($exception, $no_throw);
	}	
	
	/**
	 * @param string status 
	 * @return \ibidem\base\Layer_HTTP $this
	 */
	function status($status)
	{
		$this->status = $status;
		return $this;
	}
	
	/**
	 * Used to set content type. If you're trying to use XHTML for example, the
	 * content type (or at least the correct one) is not text/html :)
	 * 
	 * @param string content-type
	 * @return \ibidem\base\Layer_HTTP $this
	 */
	function content_type($content_type)
	{
		$this->params['content-type'] = $content_type;
		return $this;
	}
	
	/**
	 * @return string
	 */
	function get_content_type()
	{
		return $this->params['content-type'];
	}
	
} # class

<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_HTTP extends \app\Layer 
	implements \ibidem\types\Meta, \ibidem\types\HTTP
{	
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
	public static function instance()
	{
		$instance = parent::instance();
		$http = \app\CFS::config('ibidem/http');
		$instance->meta = $http['meta'];
		$instance->status = $http['status'];
		return $instance;
	}	
	
	/**
	 * @return string location identifier (null if not found)
	 */
	public static function detect_uri()
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

		return $detected_uri = $uri;
	}
	
	/**
	 * @return string 
	 */
	public static function detect_ip()
	{
		if 
		(
			isset($_SERVER['HTTP_X_FORWARDED_FOR'])
			&& isset($_SERVER['REMOTE_ADDR'])
			&& \in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost', 'localhost.localdomain'))
		)
		{
			// Use the forwarded IP address, typically set when the
			// client is using a proxy server.
			// Format: "X-Forwarded-For: client1, proxy1, proxy2"
			$client_ips = \explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			return \array_shift($client_ips);
		}
		elseif 
		(
			isset($_SERVER['HTTP_CLIENT_IP'])
			&& isset($_SERVER['REMOTE_ADDR'])
			&& \in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost', 'localhost.localdomain'))
		)
		{
			// use the forwarded IP address, typically set when the
			// client is using a proxy server.
			$client_ips = \explode(',', $_SERVER['HTTP_CLIENT_IP']);

			return \array_shift($client_ips);
		}
		elseif (isset($_SERVER['REMOTE_ADDR']))
		{
			// the remote IP address
			return $_SERVER['REMOTE_ADDR'];
		}
		
		return '0.0.0.0';
	}
	
	/**
	 * @param string url 
	 */
	public static function redirect_to_url($url)
	{
		\header('Location: '.$url);
		die;
	}
	
	/**
	 * @param string relay
	 * @param array params 
	 */
	public static function redirect($relay, array $params = null, array $query = null)
	{
		$access_config = \app\CFS::config('ibidem/relays');
		if ($query == null)
		{
			static::redirect_to_url($access_config[$relay]['route']->url($params));
		}
		else # non-null query
		{
			$query = \http_build_query($query);
			static::redirect_to_url($access_config[$relay]['route']->url($params).'?'.$query);
		}
	}
	
	/**
	 * @return string url base
	 */
	public static function detect_url_base()
	{
		return $_SERVER['SERVER_NAME'].
			($_SERVER['SERVER_PORT'] !== 80 ? ':'.$_SERVER['SERVER_PORT'] : '');
	}
		
	/**
	 * @return string
	 */
	public static function request_method()
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			// Use the server request method
			return \strtoupper($_SERVER['REQUEST_METHOD']);
		}
		else # REQUEST_METHOD not set
		{
			// Default to GET requests
			return \strtoupper(\ibidem\types\HTTP::GET);
		}
	}
	
	/**
	 * Executes non-content related tasks before main contents.
	 */
	public function headerinfo()
	{
		\header($this->status);
		// process meta
		foreach ($this->meta as $key => $value)
		{
			\header("$key: $value");
		}
	}
	
	/**
	 * @param \ibidem\types\Event
	 */
	public function dispatch(\ibidem\types\Event $event)
	{
		switch ($event->get_subject())
		{
			case \ibidem\types\Event::content_type:
				$this->content_type($event->get_contents());
				break;
			
			case \ibidem\types\Event::expires:
				$expiration = $event->get_contents() - \time();
				$this->meta('Pragma', 'public');
				$this->meta('Cache-Control', 'maxage='.$expiration);
				$this->meta
					(
						'Expires', 
						\gmdate('D, d M Y H:i:s', \time() + $expiration).' GMT'
					);
				break;
		}
		
		// pass to default handling
		parent::dispatch($event);
	}	
	
	/**
	 * Execute the layer.
	 */
	public function execute()
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
	function exception(\Exception $exception, $origin = false)
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
		parent::exception($exception);
	}	
	
	/**
	 * @param string status 
	 * @return \ibidem\base\Layer_HTTP $this
	 */
	public function status($status)
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
	public function content_type($content_type)
	{
		$this->meta['content-type'] = $content_type;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function get_content_type()
	{
		return $this->meta['content-type'];
	}
	
# Meta trait
	
	/**
	 * @var array 
	 */
	protected $meta;
	
	/**
	 * Set metainformation for the document.
	 * 
	 * @param string key
	 * @param mixed value
	 * @return \ibidem\base\Layer_HTTP $this
	 */
	public function meta($key, $value)
	{
		$this->meta[$key] = $value;
		
		return $this;
	}
	
	/**
	 * @param string key
	 * @param mixed default
	 * @return mixed meta value for key, or default
	 */
	public function get_meta($key, $default = null)
	{
		return isset($this->meta[$key]) ? $this->meta[$key] : $default;
	}
	
# /Meta trait
	
} # class

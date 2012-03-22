<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Layer_HTTP extends \app\Layer 
	implements \kohana4\types\Meta,	\kohana4\types\HTTP
{
	use \app\Trait_Meta;
	
	/**
	 * @var string
	 */
	protected static $layer_name = \kohana4\types\HTTP::LAYER_NAME;
	
	/**
	 * @var string
	 */
	protected $status;
	
	/**
	 * @return \kohana4\types\Layer
	 */
	public static function instance()
	{
		$instance = parent::instance();
		$http = \app\CFS::config('kohana4/http');
		$instance->meta = $http['meta'];
		$instance->status = $http['status'];
		return $instance;
	}	
	
	/**
	 * @return string location identifier (null if not found)
	 */
	public static function detect_uri()
	{
		static $detected_uri;
		
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

			$config = \app\CFS::config('kohana4/base');
			
			if ($config['url_base'] !== null)
			{
				// Get the path from the base URL, including the index file
				$base_url = \parse_url($config['url_base'], PHP_URL_PATH);

				if (\strpos($uri, $base_url) === 0)
				{
					// Remove the base URL from the URI
					$uri = (string) \substr($uri, \strlen($base_url));
				}
			}

			if ($config['index_file'] && \strpos($uri, $config['index_file']) === 0)
			{
				// Remove the index file from the URI
				$uri = (string) \substr($uri, \strlen($config['index_file']));
			}
		}

		return $detected_uri = $uri;
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
		return \is_a($exception, '\\kohana4\\types\\Exception') 
			&& $exception->get_type() === \kohana4\types\Exception::NotFound;
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
			$this->status(\kohana4\types\HTTP::STATUS_NotFound);
		}
		else # some error we don't know about
		{
			$this->status(\kohana4\types\HTTP::STATUS_InternalServerError);
		}
		
		// default execution from Layer
		parent::exception($exception);
	}	
	
	/**
	 * @param string status 
	 * @return $this
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
	 * @return $this
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
	
} # class

<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Layer_MVC extends \app\Layer 
	implements 
		\kohana4\types\Meta,
		\kohana4\types\RelayCompatible,
		\kohana4\types\Pattern_MVC
{
	/**
	 * @var string
	 */
	protected static $layer_name = \kohana4\types\Pattern_MVC::LAYER_NAME;
	
	/**
	 * @var array 
	 */
	protected $meta;
	
	/**
	 * @var array
	 */
	protected $relay;
	
	/**
	 * @return \kohana4\types\Layer
	 */
	public static function instance()
	{
		$instance = parent::instance();
		$instance->meta = \app\CFS::config('kohana4/mvc');
		return $instance;
	}	
		
	/**
	 * Execute the layer.
	 */
	public function execute()
	{
		try 
		{
			$relay = $this->relay;
			// relay configuration
			$this->controller( $controller = $relay['controller']::instance() );
			$this->params($relay['route']->get_params());
			$this->meta('action', $relay['action']);
			
			// dispatch events
			if (\is_a($relay['route'], '\kohana4\types\URLCompatible'))
			{
				$canonical_url = $relay['route']->canonical_url
					(array('name' => $this->meta['params']->get('name')), 'http');

				$this->dispatch
					(
						Event::instance()->contents($canonical_url)
							->subject(\kohana4\types\Event::canonical_url)
					);
			}
			
			// execute controller
			$controller->params($this->meta['params']);
			$controller->layer($this);
			$controller->before_action();
			\call_user_func(array($controller, $this->meta['action']));
			$controller->after_action();

			$this->contents($controller->get_body());
		}
		catch (\Exception $exception)
		{
			$this->exception($exception, true);
		}
	}
	
	/**
	 * Fills body and approprite calls for current layer, and passes the 
	 * exception up to be processed by the layer above, if the layer has a 
	 * parent.
	 * 
	 * @param \Exception
	 * @param boolean layer is origin of exception?
	 */
	public function exception(\Exception $exception, $origin = false)
	{
		if (\is_a($exception, '\\kohana4\\types\\Exception'))
		{
			// note: MVC isn't necesarly HTML ;) so no html tags here
			$this->contents(' '.$exception->title().': '.$exception->message());
		}
		
		// default execution from Layer
		parent::exception($exception);
	}
	
	/**
	 * @param \kohana4\types\Controller 
	 * @return $this
	 */
	public function controller(\kohana4\types\Controller $controller)
	{
		$this->meta['controller'] = $controller;
		return $this;
	}
	
	/**
	 * Action paramters.
	 * 
	 * @param \kohana4\types\Controller 
	 * @return $this
	 */
	public function params(\kohana4\types\Params $params)
	{
		$this->meta['params'] = $params;
		return $this;
	}
	
	/**
	 * @param \kohana4\types\Layer layer
	 * @param \kohana4\types\Layer parent
	 * @return \kohana4\types\Layer 
	 */
	public function register(\kohana4\types\Layer $layer)
	{
		// Note: In this implementation we treat MVC as a self contained pattern
		// for the sake of purity of the pattern so we don't support sub layers.
		throw \app\Exception_NotApplicable::instance
			(
				__CLASS__.' does not support sublayer.'
			);
	}
	
	/**
	 * Set metainformation for the document.
	 * 
	 * @param string key
	 * @param mixed value
	 * @return $this
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
		
	/**
	 * @param array relay configuration
	 * @return $this
	 */
	public function relay_config(array $relay)
	{
		// [!!] don't do actual configuration here; do it in the execution loop;
		// not only is it potentially unused configuration but when this is 
		// called there is also no gurantee the Layer itself is configured
		$this->relay = $relay;
		return $this;
	}
	
} # class

<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_MVC extends \app\Layer 
	implements 
		\ibidem\types\Meta,
		\ibidem\types\RelayCompatible,
		\ibidem\types\Pattern_MVC
{
	/**
	 * @var string
	 */
	protected static $layer_name = \ibidem\types\Pattern_MVC::LAYER_NAME;
	
	/**
	 * @var array 
	 */
	protected $meta;
	
	/**
	 * @var array
	 */
	protected $relay;
	
	/**
	 * @return \ibidem\types\Layer
	 */
	public static function instance()
	{
		$instance = parent::instance();
		$instance->meta = \app\CFS::config('ibidem/mvc');
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
			$params = $relay['route']->get_params();
			// relay configuration
			$this->controller( $controller = $relay['controller']::instance() );
			$this->params($params);
			
			if (isset($relay['action']))
			{
				$this->meta('action', $relay['action']);
			}
			else # action not predefined
			{
				$this->meta('action', 'action_'.$params->get('action'));
			}
			
			// execute controller
			$controller->params($params);
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
		if (\is_a($exception, '\\ibidem\\types\\Exception'))
		{
			// note: MVC isn't necesarly HTML ;) so no html tags here
			$this->contents(' '.$exception->title().': '.$exception->message());
		}
		
		// default execution from Layer
		parent::exception($exception);
	}
	
	/**
	 * @param \ibidem\types\Controller 
	 * @return $this
	 */
	public function controller(\ibidem\types\Controller $controller)
	{
		$this->meta['controller'] = $controller;
		return $this;
	}
	
	/**
	 * Action paramters.
	 * 
	 * @param \ibidem\types\Controller 
	 * @return $this
	 */
	public function params(\ibidem\types\Params $params)
	{
		$this->meta['params'] = $params;
		return $this;
	}
	
	/**
	 * @param \ibidem\types\Layer layer
	 * @param \ibidem\types\Layer parent
	 * @return \ibidem\types\Layer 
	 */
	public function register(\ibidem\types\Layer $layer)
	{
		// Note: In this implementation we treat MVC as a self contained pattern
		// for the sake of purity of the pattern so we don't support sub layers.
		throw new \app\Exception_NotApplicable
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

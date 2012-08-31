<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_MVC extends \app\Layer 
	implements 
		\ibidem\types\Params,
		\ibidem\types\RelayCompatible,
		\ibidem\types\Pattern_MVC
{
	use \app\Trait_Params;
	
	/**
	 * @var string
	 */
	protected static $layer_name = \ibidem\types\Pattern_MVC::LAYER_NAME;
	
	/**
	 * @var array
	 */
	protected $relay;
	
	/**
	 * @return \ibidem\base\Layer_MVC
	 */
	static function instance()
	{
		$instance = parent::instance();
		$instance->params = \app\CFS::config('ibidem/mvc');
		return $instance;
	}	
		
	/**
	 * Execute the layer.
	 */
	function execute()
	{
		try 
		{
			$relay = $this->relay;
			$params = $relay['matcher']->get_params();
			// relay configuration
			$this->controller( $controller = $relay['controller']::instance() );
			$this->params($params);
			
			if ($params->get('action') === null && isset($relay['action']))
			{
				$this->set('action', $relay['action']);
			}
			else # action not predefined
			{
				if (isset($relay['prefix']))
				{
					$action = $params->get('action', null);
					if ($action !== null)
					{
						$action = \str_replace('-', '_', $action);
						$this->set('action', $relay['prefix'].$action);
					}
					else # $action === null
					{
						throw new \app\Exception_NotApplicable
							('Undefined default action for matched route.');
					}
				}
				else # prefix not set
				{
					$action = $params->get('action', null);
					if ($action !== null)
					{
						$action = \str_replace('-', '_', $action);
						$this->set('action', 'action_'.$action);
					}
					else # $action === null
					{
						throw new \app\Exception_NotApplicable
							('Undefined default action for matched route.');
					}
				}
			}
			
			// execute controller
			$controller->params($params);
			$controller->layer($this);
			$controller->before();
			\call_user_func(array($controller, $this->params['action']));
			$controller->after();

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
	function exception(\Exception $exception, $no_throw = false, $origin = false)
	{
		if (\is_a($exception, '\\ibidem\\types\\Exception'))
		{
			$error = ' '.$exception->title().': '.$exception->message()."\n";
			if (\app\Layer::find('html') !== null) {
				$error .= '<pre>';
			}
			$error .= \str_replace(DOCROOT, '', $exception->getTraceAsString());
			$this->contents($error);
		}
		
		// default execution from Layer
		parent::exception($exception, $no_throw);
	}
	
	/**
	 * @param \ibidem\types\Controller 
	 * @return \ibidem\base\Layer_MVC $this
	 */
	function controller(\ibidem\types\Controller $controller)
	{
		$this->params['controller'] = $controller;
		return $this;
	}
	
	/**
	 * Action paramters.
	 * 
	 * @param \ibidem\types\Controller 
	 * @return \ibidem\base\Layer_MVC $this
	 */
	function params(\ibidem\types\Params $params)
	{
		$this->params['params'] = $params;
		return $this;
	}
	
	/**
	 * @return \ibidem\types\Controller
	 */
	function current_controller()
	{
		return $this->params['controller'];
	}
	
	/**
	 * @param \ibidem\types\Layer layer
	 * @param \ibidem\types\Layer parent
	 * @return \ibidem\base\Layer_MVC $this
	 */
	function register(\ibidem\types\Layer $layer)
	{
		// Note: In this implementation we treat MVC as a self contained pattern
		// for the sake of purity of the pattern so we don't support sub layers.
		throw new \app\Exception_NotApplicable
			(
				__CLASS__.' does not support sublayer.'
			);
	}
		
	/**
	 * @param array relay configuration
	 * @return \ibidem\base\Layer_MVC $this
	 */
	function relay_config(array $relay)
	{
		// [!!] don't do actual configuration here; do it in the execution loop;
		// not only is it potentially unused configuration but when this is 
		// called there is also no gurantee the Layer itself is configured
		$this->relay = $relay;
		return $this;
	}
	
} # class

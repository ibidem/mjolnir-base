<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Controller
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Web extends \app\Controller
{
	/**
	 * @var string target
	 */
	protected static $target = null;
	
	/**
	 * @var mixed
	 */
	protected $context = null;
	
	/**
	 * Creates the context.
	 * 
	 * @return mixed context
	 */
	protected function make_context()
	{
		$class = '\app\Context_'.\ucfirst(static::$target);
		return $class::instance();
	}
	
	/**
	 * Execute before actions.
	 */
	function before()
	{
		$this->context = $this->make_context();
	}
	
	/**
	 * Index action.
	 */
	function action_index()
	{
		$this->body
			(
				\app\ThemeView::instance()
					->target(static::$target)
					->layer($this->layer)
					->context($this->context)
					->control($this)
					->render()
			);
	}	
	
	/**
	 * @return string
	 */
	function method()
	{
		return \app\Server::request_method();
	}
	
	/**
	 * @param string action
	 * @return string 
	 */	
	function action($action)
	{
		$relay = $this->layer->get_relay();
		return $relay['route']->url(array('action' => $action));
	}

} # class

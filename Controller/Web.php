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
	 * Shorthand for redirecting the user to another page after completing an 
	 * operations.
	 */
	protected function forward($relay, array $params = null)
	{
		\app\Server::redirect(\app\URL::href($relay, $params));
	}
	
	/**
	 * Execute before actions.
	 */
	function before()
	{
		if (static::$target !== null && \app\Server::request_method() === 'GET')
		{
			$this->context = $this->make_context();
		}
	}
	
	/**
	 * @return \app\ThemeView
	 */
	function themeview()
	{
		return \app\ThemeView::instance()
			->layer($this->layer)
			->context($this->context)
			->control($this);
	}
	
	/**
	 * Index action.
	 */
	function action_index()
	{
		$this->body
			(
				$this->themeview()
					->target(static::$target)
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
	
	/**
	 * Sugar for the `action` method.
	 * 
	 * @return string
	 */
	function act($action)
	{
		return $this->action($action);
	}

} # class

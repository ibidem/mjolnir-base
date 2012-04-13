<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_HTTP extends \app\Controller
{
	/**
	 * @return string
	 */
	public function method()
	{
		return \app\Layer_HTTP::request_method();
	}
	
	/**
	 * @param string action
	 * @return string 
	 */	
	public function action($action)
	{
		$relay = $this->layer->get_relay();
		return $relay['route']->url(array('action' => $action));
	}
	
} # class

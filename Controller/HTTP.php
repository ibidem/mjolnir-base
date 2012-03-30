<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Controller_HTTP extends \app\Controller
{
	public function method()
	{
		return \app\Layer_HTTP::request_method();
	}
	
} # class

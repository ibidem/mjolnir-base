<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller extends \app\Instantiatable
	implements 
		\ibidem\types\Document, 
		\ibidem\types\Controller
{
	use \app\Trait_Document;
	
	/**
	 * @var \ibidem\types\Params
	 */
	protected $params;
	
	/**
	 * @var \ibidem\types\Layer
	 */
	protected $layer;
	
	/**
	 * @param \ibidem\types\Layer
	 * @return \ibidem\base\Controller $this
	 */
	function layer(\ibidem\types\Layer $layer)
	{
		$this->layer = $layer;
	}
	
	/**
	 * @return \ibidem\base\Controller $this
	 */
	function before()
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Controller $this
	 */
	function after()
	{
		return $this;
	}
	
	/**
	 * @param \ibidem\types\Params
	 * @return \ibidem\base\Controller $this
	 */
	function params(\ibidem\types\Params $params)
	{
		$this->params = $params;
		return $this;
	}
	
	/**
	 * @return \ibidem\types\Params
	 */
	function get_parameters()
	{
		return $this->params;
	}
	
} # class

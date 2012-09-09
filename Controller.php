<?php namespace mjolnir\base;

/** 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller extends \app\Instantiatable
	implements 
		\mjolnir\types\Document, 
		\mjolnir\types\Controller
{
	use \app\Trait_Document;
	
	/**
	 * @var \mjolnir\types\Params
	 */
	protected $params;
	
	/**
	 * @var \mjolnir\types\Layer
	 */
	protected $layer;
	
	/**
	 * @param \mjolnir\types\Layer
	 * @return \mjolnir\base\Controller $this
	 */
	function layer(\mjolnir\types\Layer $layer)
	{
		$this->layer = $layer;
	}
	
	/**
	 * @return \mjolnir\base\Controller $this
	 */
	function before()
	{
		return $this;
	}
	
	/**
	 * @return \mjolnir\base\Controller $this
	 */
	function after()
	{
		return $this;
	}
	
	/**
	 * @param \mjolnir\types\Params
	 * @return \mjolnir\base\Controller $this
	 */
	function params(\mjolnir\types\Params $params)
	{
		$this->params = $params;
		return $this;
	}
	
	/**
	 * @return \mjolnir\types\Params
	 */
	function get_parameters()
	{
		return $this->params;
	}
	
} # class

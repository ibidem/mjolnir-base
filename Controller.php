<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller extends \app\Instantiatable
	implements \ibidem\types\Document, \ibidem\types\Controller
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
	 * @return $this
	 */
	public function layer(\ibidem\types\Layer $layer)
	{
		$this->layer = $layer;
	}
	
	/**
	 * @return $this 
	 */
	public function before_action()
	{
		return $this;
	}
	
	/**
	 * @return $this 
	 */
	public function after_action()
	{
		return $this;
	}
	
	/**
	 * @param \ibidem\types\Params
	 * @return $this
	 */
	public function params(\ibidem\types\Params $params)
	{
		$this->params = $params;
		return $this;
	}
	
} # class

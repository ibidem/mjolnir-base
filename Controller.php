<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Controller extends \app\Instantiatable
	implements 
		\kohana4\types\Document,
		\kohana4\types\Controller
{
	use \app\Trait_Document;
	
	protected $params;
	
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
	 * @param \kohana4\types\Params
	 * @return $this
	 */
	public function params(\kohana4\types\Params $params)
	{
		$this->params = $params;
		return $this;
	}
	
} # class

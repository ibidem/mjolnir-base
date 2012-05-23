<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller extends \app\Instantiatable
	implements \ibidem\types\Document, \ibidem\types\Controller
{
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
	public function layer(\ibidem\types\Layer $layer)
	{
		$this->layer = $layer;
	}
	
	/**
	 * @return \ibidem\base\Controller $this
	 */
	public function before()
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Controller $this
	 */
	public function after()
	{
		return $this;
	}
	
	/**
	 * @param \ibidem\types\Params
	 * @return \ibidem\base\Controller $this
	 */
	public function params(\ibidem\types\Params $params)
	{
		$this->params = $params;
		return $this;
	}
	
# Document trait
	
	/**
	 * @var string 
	 */
	protected $body = '';
		
	/**
	 * Set the document's body.
	 * 
	 * @param string document body
	 * @return \ibidem\base\Controller $this
	 */
	public function body($body)
	{
		$this->body = $body;
		return $this;
	}
	
	/**
	 * Retrieve the body.
	 * 
	 * @return string body of document 
	 */
	public function get_body()
	{
		return $this->body;
	}
	
# /Document trait	
	
} # class

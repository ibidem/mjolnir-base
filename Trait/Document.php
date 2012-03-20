<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
trait Trait_Document
{
	/**
	 * @var string 
	 */
	protected $body = '';
		
	/**
	 * Set the document's body.
	 * 
	 * @param string document body
	 * @return $this
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
	
} # trait

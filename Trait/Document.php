<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
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
	 * @return \ibidem\base\Controller $this
	 */
	function body($body)
	{
		$this->body = $body;
		return $this;
	}
	
	/**
	 * Retrieve the body.
	 * 
	 * @return string body of document 
	 */
	function get_body()
	{
		return $this->body;
	}
	
} # trait
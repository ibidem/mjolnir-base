<?php namespace kohana4\base;

/** 
 * Extremely simple Layer.
 * 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Layer_PlainText extends \app\Layer 
	implements 
		\kohana4\types\Document
{
	/**
	 * @var string
	 */
	protected static $layer_name = 'http';
	
	protected $body = '';
	
	/**
	 * Set the document's body.
	 * 
	 * @param string document body
	 * @return $this
	 */
	public function body($body)
	{
		if ($this->layer !== null)
		{
			throw \app\Exception::instance("Can't have both a body and contents.")
				->type(\kohana4\types\Exception::NotApplicable);
		}
		
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

	/**
	 * Execute the layer.
	 */
	public function execute()
	{
		try
		{
			parent::execute();
			// got sublayer?
			if ($this->layer)
			{
				// it is possible this is used higher up
				$this->contents($this->layer->get_contents());
			}
			else # no sublayer
			{
				$this->contents($this->get_body());
			}
			// meta information
			\header("content-type: text/plain");
		}
		catch (\Exception $exception)
		{
			$this->exception($exception);
		}
	}

} # class

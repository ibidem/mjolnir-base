<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Layer_PlainText extends \app\Layer 
	implements \kohana4\types\Document
{	
	use Trait_Document 
	{
		Trait_Document::body as private Document_body;
	}
	
	/**
	 * @var string
	 */
	protected static $layer_name = \kohana4\types\Layer::DEFAULT_LAYER_NAME;
	
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
		
		$this->Document_body($body);
	}
	
	/**
	 * Executes non-content related tasks before main contents.
	 */
	public function headerinfo()
	{
		// meta information
		\header("content-type: text/plain");
		parent::headerinfo();
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
		}
		catch (\Exception $exception)
		{
			$this->exception($exception);
		}
	}

} # class

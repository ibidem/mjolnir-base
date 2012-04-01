<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_PlainText extends \app\Layer 
	implements \ibidem\types\Document
{	
	use Trait_Document 
	{
		Trait_Document::body as private Document_body;
	}
	
	/**
	 * @var string
	 */
	protected static $layer_name = \ibidem\types\Layer::DEFAULT_LAYER_NAME;
	
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
				->type(\ibidem\types\Exception::NotApplicable);
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

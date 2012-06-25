<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_PlainText extends \app\Layer 
	implements \ibidem\types\Document
{
	use \app\Trait_Document
		{
			\app\Trait_Document::body as document_body;
		}
	
	/**
	 * @var string
	 */
	protected static $layer_name = \ibidem\types\Layer::DEFAULT_LAYER_NAME;
	
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
	
# Document trait

	/**
	 * Set the document's body.
	 * 
	 * @param string document body
	 * @return \ibidem\base\Layer_PlainText $this
	 */
	public function body($body)	
	{
		if ($this->layer !== null)
		{
			$exception = new \app\Exception
				(
					'Can\'t have both a body and contents.'
				);
				
			throw $exception->type(\ibidem\types\Exception::NotApplicable);
		}
		
		$this->document_body($body);
		
		return $this;
	}

# /Document trait

} # class

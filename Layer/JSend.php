<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Layer
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_JSend extends \app\Layer
{
	/**
	 * Execute the layer.
	 */
	function execute()
	{
		try
		{
			parent::execute();
			// got sublayer?
			if ($this->layer)
			{
				$contents = $this->layer->get_contents();
				
				$contents = \json_encode
					(
						[
							'status' => 'success',
							'data' => & $contents,
						]
					);
				
				// it is possible this is used higher up
				$this->contents($contents);
			}
		}
		catch (\Exception $exception)
		{
			$message = 'An unknown error has occured.';
			
			if (\app\CFS::config('mjolnir/base')['development'])
			{
				$message = $exception->getMessage();
			}
			else # public; recoverable errors
			{
				if (\is_a($exception, '\app\Exception_NotAllowed'))
				{
					$message = 'The operation has failed due to insuficient access rights.';
				}
				else if (\is_a($exception, '\app\Exception_NotApplicable'))
				{
					$message = $exception->getMessage();
				}
			}
			
			$this->contents
				(
					\json_encode
					(
						[
							'status' => 'error',
							'message' => $message
						]
					)
				);
			
			$this->exception($exception);
		}
	}
	
} # class

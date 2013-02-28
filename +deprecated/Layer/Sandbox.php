<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_Sandbox extends \app\Layer
{
	/**
	 * @var callback 
	 */
	private $callback;
	
	/**
	 * @var array
	 */
	private $relay;
	
	/**
	 * @param array relay
	 * @return static $this
	 */
	function relay($relay)
	{
		$this->relay = $relay;
		return $this;
	}
	
	/**
	 * Execute the layer.
	 */
	function execute()
	{
		try
		{
			$callback = $this->callback;
			$this->contents($callback($this->relay));
		}
		catch (\Exception $exception)
		{
			$this->contents($exception->getMessage());
			$this->exception($exception);
		}
	}
	
	/**
	 * @param callback callback
	 * @return static $this
	 */
	function caller($callback)
	{
		$this->callback = $callback;
		return $this;
	}

} # class

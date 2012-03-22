<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Layer extends \app\Instantiatable 
	implements \kohana4\types\Layer
{	
	/**
	 * @var \kohana4\types\Layer
	 */
	protected static $top;
	
	/**
	 * @var string
	 */
	protected static $layer_name = \kohana4\types\Layer::DEFAULT_LAYER_NAME;
	
	/**
	 * @var \kohana4\types\Layer
	 */
	protected $layer;

	/**
	 * @var \kohana4\types\Layer 
	 */
	protected $parent;

	/**
	 * @var string
	 */
	protected $contents;
	
	/**
	 * @return string layer name of self 
	 */
	public function get_layer_name()
	{
		return static::$layer_name;
	}
	
	/**
	 * @param \kohana4\types\Layer layer
	 * @param \kohana4\types\Layer parent
	 * @return \kohana4\types\Layer 
	 */
	public function register(\kohana4\types\Layer $layer)
	{
		if ($this->layer)
		{
			throw \app\Exception_NotApplicable::instance
				(
					'Document layer already defined; layer does not accept multiple documents.'
				);
		}
		
		$this->layer = $layer;
		$this->layer->parent_layer($this);

		return $this;
	}
	
	/**
	 * @param \kohana4\types\Layer $parent
	 * @return \kohana4\base\Layer
	 */
	public function parent_layer(\kohana4\types\Layer $parent)
	{
		$this->parent = $parent;
		return $this;
	}
	
	/**
	 * Executes non-content related tasks before main contents.
	 */
	public function headerinfo()
	{
		if ($this->layer !== null)
		{
			$this->layer->headerinfo();
		}
	}
	
	/**
	 * Execute the layer.
	 */
	public function execute()
	{
		try 
		{
			if ($this->layer !== null)
			{
				$this->layer->execute();
			}
		}
		catch (\Exception $e)
		{
			$this->exception($e);
		}
	}
	
	/**
	 * Layer contents; if any. Or null, for no contents.
	 * 
	 * [!!] This method doesn't necesarly accept a string
	 * 
	 * @param mixed contents
	 * @return $this
	 */
	protected function contents($contents = null)
	{
		$this->contents = $contents;
		return $this;
	}
	
	/**
	 * [!!] This method doesn't necesarly return string
	 * 
	 * @return mixed
	 */
	public function get_contents()
	{
		return $this->contents;
	}
	
	/**
	 * Get the Layer with the specified name. The current layer will try to find 
	 * the layer in it's registered layers, and if it fails it will ask for it
	 * from each component.
	 * 
	 * @param string layer name
	 * @return \kohana4\types\Layer
	 * @throws \kohana4\types\Exception
	 */
	public function get_layer($layer_name)
	{
		// this layer?
		if ($layer_name === static::LAYER_NAME)
		{
			return $this;
		}
		
		// a layer we know?
		if ($this->layer !== null)
		{
			return $this->layer->get($layer_name);
		}
		
		// invalid layer name given
		throw \app\Exception_Error::instance('Invalid layer: '.$layer_name);
	}
	
	/**
	 * Register the top layer of the system
	 * 
	 * @param \kohana4\types\Layer top layer
	 */
	public static function top(\kohana4\types\Layer $top_layer)
	{
		static::$top = $top_layer;
	}
	
	/**
	 * @return \kohana4\types\Layer top layer
	 */
	public static function get_top()
	{
		return static::$top;
	}
	
	/**
	 * Fills body and approprite calls for current layer, and passes the 
	 * exception up to be processed by the layer above, if the layer has a 
	 * parent.
	 * 
	 * @param \Exception
	 * @param boolean layer is origin of exception?
	 */
	public function exception(\Exception $exception, $origin = false)
	{
		// propagate contents; contents should not be set unless they were set
		// by an overwrite on this method... because setting the contents should
		// be the last part of the execution and if the exception triggered it's
		// almost guranteed it didn't go though
		if ($this->contents === null && $this->layer !== null && ! $origin)
		{
			$this->contents($this->layer->get_contents());
		}
		
		// pass to parent
		if ($this->parent)
		{
			$this->parent->exception($exception);
		}
		else # no parent
		{
			// we can't do anything about it anymore
			throw $exception;
		}
	}
	
	/**
	 * Captures a broadcast Event.
	 * 
	 * @param \kohana4\types\Event
	 */
	public function capture(\kohana4\types\Event $event)
	{
		if ($this->layer)
		{
			$this->layer->capture($event);
		}
	}
	
	/**
	 * Sends an Event to the parent of the current layer.
	 * 
	 * @param \kohana4\types\Event
	 */
	public function dispatch(\kohana4\types\Event $event)
	{
		if ($this->parent)
		{
			$this->parent->dispatch($event);
		}
	}
	
	/**
	 * Send an Event to the top layer and then down
	 * 
	 * @param \kohana4\types\Event
	 */
	public static function broadcast(\kohana4\types\Event $event)
	{
		static::$top->capture($event);
	}
	
	/**
	 * Same as get on instance level. This method simply calls the top layer and
	 * invokes get.
	 * 
	 * @param string layer name
	 * @return \kohana4\types\Layer
	 * @throws \kohana4\types\Exception
	 */
	public static function find($layer_name)
	{
		return static::$top->get_layer($layer_name);
	}
	
	/**
	 * Initializes execution, starting at the top. 
	 */
	public static function run()
	{
		try 
		{
			static::$top->execute();
			$contents = static::$top->get_contents();
			static::$top->headerinfo();
			if (\is_string($contents) || \method_exists($contents, '__toString'))
			{
				echo $contents;
			}
		}
		catch (\kohana4\types\Exception $exception)
		{
			$contents = static::$top->get_contents();
			static::$top->headerinfo();
			if (\is_string($contents) || \method_exists($contents, '__toString'))
			{
				echo $contents;
			}
		}
		catch (\Exception $exception)
		{
			throw $exception;
		}
	}
	
	/**
	 * Shortcut method for setting up a stack.
	 * @param \kohana4\types\Layer $args
	 */
	public static function stack($args)
	{
		$args = \func_get_args();
		$first = \array_shift($args);
		static::top($first);
		foreach ($args as $arg)
		{
			$first->register($arg);
			$first = $arg;
		}
		static::run();
	}
	
} # class

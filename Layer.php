<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
abstract class Layer extends \app\Instantiatable
	implements \mjolnir\types\Layer
{
	/**
	 * @var \mjolnir\types\Layer
	 */
	protected static $top;

	/**
	 * @var string
	 */
	protected static $layer_name = \mjolnir\types\Layer::DEFAULT_LAYER_NAME;

	/**
	 * @var \mjolnir\types\Layer
	 */
	protected $layer;

	/**
	 * @var \mjolnir\types\Layer
	 */
	protected $parent;

	/**
	 * @var string
	 */
	protected $contents;

	/**
	 * @return string layer name of self
	 */
	function get_layer_name()
	{
		return static::$layer_name;
	}

	/**
	 * @param \mjolnir\types\Layer layer
	 * @param \mjolnir\types\Layer parent
	 * @return \mjolnir\base\Layer $this
	 */
	function register(\mjolnir\types\Layer $layer)
	{
		if ($this->layer)
		{
			throw new \app\Exception
				(
					'Document layer already defined; layer does not accept multiple documents.'
				);
		}

		$this->layer = $layer;
		$this->layer->parent_layer($this);

		return $this;
	}

	/**
	 * @param \mjolnir\types\Layer $parent
	 * @return \mjolnir\base\Layer $this
	 */
	function parent_layer(\mjolnir\types\Layer $parent)
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * Executes non-content related tasks before main contents.
	 */
	function headerinfo()
	{
		if ($this->layer !== null)
		{
			$this->layer->headerinfo();
		}
	}

	/**
	 * Execute the layer.
	 */
	function execute()
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
	 * @return \mjolnir\base\Layer $this
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
	function get_contents()
	{
		return $this->contents;
	}

	/**
	 * Get the Layer with the specified name. The current layer will try to find
	 * the layer in it's registered layers, and if it fails it will ask for it
	 * from each component.
	 *
	 * @param string layer name
	 * @return \mjolnir\base\Layer $this
	 * @throws \mjolnir\types\Exception
	 */
	function get_layer($layer_name)
	{
		// this layer?
		if ($layer_name === static::$layer_name)
		{
			return $this;
		}

		// a layer we know?
		if ($this->layer !== null)
		{
			return $this->layer->get_layer($layer_name);
		}

		return null;
	}

	/**
	 * Register the top layer of the system
	 *
	 * @param \mjolnir\types\Layer top layer
	 */
	static function top(\mjolnir\types\Layer $top_layer)
	{
		static::$top = $top_layer;
	}

	/**
	 * @return \mjolnir\types\Layer top layer
	 */
	static function get_top()
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
	function exception(\Exception $exception, $no_throw = false, $origin = false)
	{
		// propagate contents; contents should not be set unless they were set
		// by an overwrite on this method... because setting the contents should
		// be the last part of the execution and if the exception triggered it's
		// almost guranteed it didn't go though
		if ($this->contents === null && $this->layer !== null && ! $origin)
		{
			$this->contents($this->layer->get_contents());
		}
		else if ($this->layer === null)
		{
			\mjolnir\exception_handler($exception);	
			exit;
		}

		// pass to parent
		if ($this->parent)
		{
			$this->parent->exception($exception, $no_throw);
		}
		else # no parent
		{
			if ( ! $no_throw)
			{
				// we can't do anything about it anymore
				throw $exception;
			}
			else if (\php_sapi_name() === 'cli')
			{
				echo $exception->getMessage()."\n"
					. \str_replace(DOCROOT, '', $exception->getTraceAsString());

				exit(1);
			}
		}
	}

	/**
	 * Same as get on instance level. This method simply calls the top layer and
	 * invokes get.
	 *
	 * @param string layer name
	 * @return \mjolnir\types\Layer
	 * @throws \mjolnir\types\Exception
	 */
	static function find($layer_name)
	{
		return static::$top->get_layer($layer_name);
	}

	/**
	 * Initializes execution, starting at the top.
	 */
	static function run()
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
		catch (\mjolnir\types\Exception $exception)
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
	 * @param \mjolnir\types\Layer $args
	 */
	static function stack($args)
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

	/**
	 * @return array
	 */
	function get_relay()
	{
		return $this->relay;
	}

} # class

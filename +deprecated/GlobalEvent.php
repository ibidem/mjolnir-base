<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class GlobalEvent
	implements \mjolnir\types\GlobalEvent
{
	/**
	 * @var array
	 */
	protected static $handlers = [];
	
	/**
	 * Notifies all registered event handlers of $event.
	 * 
	 * @param string event
	 * @param mixed parameter data
	 */
	static function fire($event, $params = null)
	{
		if (isset(static::$handlers[$event]))
		{
			foreach (static::$handlers[$event] as $handler)
			{
				$handler($params);
			}	
		}
	}
	
	/**
	 * Registers the handler to the event. When an event is fired, the handler
	 * will be called. The handler will recieved any additional paramters passed
	 * to the event.
	 * 
	 * @param string event
	 * @param callback
	 */
	static function listener($event, $handler)
	{
		if ( ! isset(static::$handlers[$event]))
		{
			static::$handlers[$event] = [];
		}
		
		static::$handlers[$event][] = $handler;
	}

} # class

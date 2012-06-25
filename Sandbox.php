<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Sandbox extends \app\Instantiatable
{
	static function process($caller, $stack = null)
	{
		if ($stack === null)
		{
			$stack = function ($relay) use ($caller)
				{
					\app\Layer::stack
						(
							\app\Layer_HTTP::instance(),
							\app\Layer_Sandbox::instance()
								->relay($relay)
								->caller($caller)
						);
				};
		}
		
		\app\Relay::process('\ibidem\sandbox', $stack);
	}

} # class

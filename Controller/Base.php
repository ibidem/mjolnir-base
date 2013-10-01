<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Controller
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
abstract class Controller_Base extends \app\Puppet implements \mjolnir\types\Controller
{
	use \app\Trait_Controller;

	/**
	 * @return string singular name for puppet
	 */
	static function singular()
	{
		if (isset(static::$grammar))
		{
			return static::$grammar[0];
		}
		else # grammar not set, attempt to retrieve from name
		{
			return strtolower(\str_replace('_', ' ', \preg_replace('/.*\\\Controller_/', '', \get_called_class())));
		}
	}

} # class

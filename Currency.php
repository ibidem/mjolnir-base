<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Currency
{
	/**
	 * Retrieve current system currency information. Only currencies defined in
	 * mjolnir/currency are retrieved. This method will add the exchange rates
	 * and return the types as-is defined in the configuration.
	 * 
	 * @returna array
	 */
	static function information()
	{
		$conf = \app\CFS::config('mjolnir/currency');
		$driver = "\app\CurrencyRates_{$conf['driver']}";
		return $driver::instance()->process($conf['types']);
	}

} # class

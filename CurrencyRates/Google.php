<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   CurrencyRates
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class CurrencyRates_Google extends \app\Instantiatable implements \mjolnir\types\CurrencyRates
{
	use \app\Trait_CurrencyRates;

	/**
	 * @return array
	 */
	function process($types)
	{
		$conf = \app\CFS::config('mjolnir/currency')['driver:Google'];

		$stashkey = 'mjolnir:Currency:drivers:Google.rates';
		$stash = \app\Stash_File::instance(false); # forced on
		$rates = $stash->get($stashkey, null);

		if ($this->is_missing_rates($rates, $types))
		{
			// calculate caching period
			$requests = \count($types);

			if (isset($types['USD']))
			{
				$requests--;
			}

			$cachetime = $requests * $conf['cache_multiplier'];

			$rates = [ 'USD' => 1 ];
			foreach ($types as $currency => $information)
			{
				if ($currency !== 'USD')
				{
					$req = "http://rate-exchange.appspot.com/currency?from=$currency&to=USD";
					$json = \app\Web::getjson($req);

					if (isset($json['err']))
					{
						throw new \app\Exception('Error retrieving rate for ['.$currency.']. Failed with message: '.$json['err']);
					}

					$rates[$currency] = $json['rate'];
				}
			}

			// cache the rates
			$stash->set($stashkey, $rates, $cachetime);
		}

		foreach ($types as $currency => & $information)
		{
			$information['rate'] = $rates[$currency];
		}

		return $types;
	}

	/**
	 * @return boolean
	 */
	private function is_missing_rates($rates, $types)
	{
		return $rates == null
			|| \count(\array_diff(\array_keys($rates), \array_keys($types))) > 0;
	}

} # class

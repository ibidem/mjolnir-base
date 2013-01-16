<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Text
{
	/**
	 * @return string
	 */
	static function camelcase_from_dashcase($dashcase)
	{
		return \app\Arr::implode('', \explode('-', $dashcase), function ($k, $segment) {
			return \ucfirst($segment);
		});
	}

} # class

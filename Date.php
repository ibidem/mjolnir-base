<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Date
{
	/**
	 * @return string
	 */
	static function default_timezone_offset()
	{
		$now = new \DateTime();
		$mins = $now->getOffset() / 60;
		$sgn = ($mins < 0 ? -1 : 1);
		$mins = \abs($mins);
		$hrs = \floor($mins / 60);
		$mins -= $hrs * 60;

		return \sprintf('%+d:%02d', $hrs * $sgn, $mins);
	}

} # class

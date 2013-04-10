<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Controller
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Bootstrap extends \app\Controller
{
	/**
	 * @return \mjolnir\types\Renderable
	 */
	function json_index()
	{
		return \json_encode(\app\CFS::config('mjolnir/bootstrap'));
	}
	
} # class

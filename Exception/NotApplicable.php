<?php namespace mjolnir\base;

/** 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception_NotApplicable extends \app\Exception
{
	/**
	 * @var string
	 */
	protected $type = \mjolnir\types\Exception::NotApplicable;
	
	/**
	 * @return string title
	 */
	function title()
	{
		return empty($this->title) ? 'Not Applicable' : $this->title;
	}
	
} # class
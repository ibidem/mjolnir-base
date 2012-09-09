<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception_NotAllowed extends \app\Exception_NotApplicable
{
	/**
	 * @var string
	 */
	protected $type = \mjolnir\types\Exception::NotAllowed;
	
	/**
	 * @return string title
	 */
	function title()
	{
		return empty($this->title) ? 'Not Allowed' : $this->title;
	}

} # class

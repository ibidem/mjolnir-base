<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception_NotFound extends \app\Exception
{
	/**
	 * @var string
	 */
	protected $type = \ibidem\types\Exception::NotFound;
	
	/**
	 * @return string title
	 */
	function title()
	{
		return empty($this->title) ? 'Not Found' : $this->title;
	}
	
} # class

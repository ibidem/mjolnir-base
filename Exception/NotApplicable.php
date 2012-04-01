<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception_NotApplicable extends \app\Exception
{
	/**
	 * @var string
	 */
	protected $type = \ibidem\types\Exception::NotApplicable;
	
	/**
	 * @return string title
	 */
	public function title()
	{
		return empty($this->title) ? 'Not Applicable' : $this->title;
	}
	
} # class

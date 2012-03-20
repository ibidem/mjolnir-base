<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Exception_NotFound extends \app\Exception
{
	/**
	 * @var string
	 */
	protected $type = \kohana4\types\Exception::NotFound;
	
	/**
	 * @return string title
	 */
	public function title()
	{
		return empty($this->title) ? 'Not Found' : $this->title;
	}
	
} # class

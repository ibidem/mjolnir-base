<?php namespace kohana4\base;

/** 
 * This class serves only to gurantee a class implementing instantiatable and 
 * also avoid any errors in using instantiatable, ie. calls to the constructor.
 * 
 * It is not necesary to extend this class when implementing the interface, but
 * it is recomended as it facilitates certain operations such as mocking up 
 * classes.
 * 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Instantiatable 
	implements \kohana4\types\Instantiatable
{
	/**
	 * Private constructor to deny access to it.
	 */
	private function __construct()
	{
		// intentionally empty
	}
	
	/**
	 * @see \kohana4\types\Instantiatable
	 * @return $this instance of current class
	 */
	public static function instance()
	{
		return new static;
	}
	
} # class

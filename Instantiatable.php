<?php namespace mjolnir\base;

/** 
 * This class serves only to gurantee a class implementing instantiatable and 
 * also avoid any errors in using instantiatable, ie. calls to the constructor.
 * 
 * It is not necesary to extend this class when implementing the interface, but
 * it is recomended as it facilitates certain operations such as mocking up 
 * classes.
 * 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Instantiatable 
	implements \mjolnir\types\Instantiatable
{
	/**
	 * Private constructor to deny access to it.
	 */
	private function __construct()
	{
		// empty
	}
	
	/**
	 * @see \mjolnir\types\Instantiatable
	 * @return static
	 */
	static function instance()
	{
		return new static;
	}
	
} # class

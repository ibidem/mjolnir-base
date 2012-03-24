<?php namespace kohana4\base;

/** 
 * General flags for command line handling.
 * 
 * If you need other flags instead of extending this class you should simply
 * provide a static method in your task then use the flag as:
 * 
 *     \mynamespace\Task_Example::my_flag
 * 
 * This class is only meant to help avoid duplication of very basic flags.
 * 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Flags # static library
{
	/**
	 * Flag type.
	 * 
	 * @return string
	 */
	public static function file($idx, array $argv)
	{
		return $argv[$idx+1];
	}
	
	/**
	 * Flag type.
	 * 
	 * @since 1.0
	 * @return string
	 */
	public static function text($idx, array $argv)
	{
		return $argv[$idx+1];
	}
	
	/**
	 * Flag type.
	 * 
	 * @since 1.0
	 * @return string
	 */
	public static function path($idx, array $argv)
	{
		return $argv[$idx+1];
	}
	
} # class

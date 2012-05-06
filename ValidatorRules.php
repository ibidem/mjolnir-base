<?php namespace ibidem\base;

/**
 * This class should only ever be extended at the project level. For all other
 * cases use callbacks.
 * 
 * @package    ibidem
 * @category   Library
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ValidatorRules
{
	/**
	 * @param mixed field
	 * @return boolean 
	 */
	public static function not_empty($field)
	{
		return ! empty($field);
	}
	
	/**
	 * @param string field
	 * @param integer maxlength
	 * @return boolean 
	 */
	public static function max_length($field, $maxlength)
	{
		return \strlen($field) <= $maxlength;
	}
	
	/**
	 * @param string field
	 * @param integer minlength
	 * @return boolean 
	 */
	public static function min_length($field, $minlength)
	{
		return \strlen($field) >= $minlength;
	}
	
	public static function equal_to($field, $other)
	{
		return $field == $other;
	}

} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Library
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Collection
{
	/**
	 * Same as \imlode only has an extra processing function.
	 * 
	 * The function is mandatory; just use plain old \implode when you don't 
	 * need it.
	 * 
	 * @param string glue
	 * @param array $list
	 * @param callback func
	 * @return string 
	 */
	static function implode($glue, array $list, $func) 
	{
		\reset($list);
		if ($value = \current($list))
		{
			$glued = $func(\key($list), $value);
			
			\next($list);
			while ($value = \current($list))
			{
				$glued .= $glue.$func(\key($list), $value);
				\next($list);
			}

			return $glued;
		}
		else # no items
		{
			return '';
		}
	}
	
	/**
	 * Given an array of unique values, mirrors the array so they keys and 
	 * values are identical. 
	 * 
	 * eg.
	 *  
	 *  'alice' => 'alice',
	 *  'bob' => 'bob',
	 * 
	 * This is useful for populating select fields.
	 */
	static function mirror(array $array)
	{
		$new_array = [];
		foreach ($array as $value)
		{
			$new_array[$value] = $value;
		}
		
		return $new_array;
	}

} # class

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
	 * Given an array converts it to an associative array based on the key 
	 * and value reference provided.
	 * 
	 * @param array array
	 * @param string key reference
	 * @param string value reference
	 * @return array
	 */
	static function associative_from(array & $array, $key_ref = 'id', $value_ref = 'title')
	{
		$new_array = [];
		foreach ($array as $row)
		{
			$new_array[$row[$key_ref]] = $row[$value_ref];
		}
		
		return $new_array;
	}
	
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
	 * Given an array of unique values, mirrors the array so the keys and 
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

	/**
	 * Given an array of arrays the function gathers all the values from a given
	 * key from the inner arrays. If the key is not set in an array the function 
	 * will ignore that entry and continue processing.
	 * 
	 * @param array array of arrays
	 * @param mixed key
	 * @return array all values for that key
	 */
	static function gather(array $array, $key)
	{
		$values = [];
		foreach ($array as $sub_array)
		{
			if (isset($sub_array[$key]))
			{
				$values[] = $sub_array[$key];
			}
		}
		
		return $values;
	}
	
	/**
	 * Filter array based on filter function; the function should return true 
	 * for acceptable entries and false for unacceptable entries. Keys will be
	 * maintained in the filtered version;
	 * 
	 * @param array collection
	 * @param callback filter (key, value)
	 */
	static function filter($collection, $filter)
	{
		$filtered_collection = [];
		
		foreach ($collection as $key => $value)
		{
			if ($filter($key, $value))
			{
				$filtered_collection[$key] = $value;
			}
		}
		
		return $filtered_collection;
	}
	
	/**
	 * @return array of items manipulated by callback
	 */
	static function convert(array $items, $callback)
	{
		foreach ($items as & $item)
		{
			$item = $callback($item);
		}
		
		return $items;
	}
	
} # class

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
	function implode($glue, array $list, $func) {
		$glued = '';
		
		$list_count = \count($list);
		
		if ($list_count != 0)
		{
			$glued = $func($list[0]);
		}
		else # no items
		{
			return $glued;
		}
		
		for ($i = 1; $i < $list_count; ++$i)
		{
			$glued .= $glue.$func($list[0]);
		}
		
		return $glued;
	}

} # class

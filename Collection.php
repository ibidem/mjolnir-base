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
			$glued = $func(\key($list), $list[\value($list)]);
			
			\next($list);
			while ($value = \current($list))
			{
				$glued .= $glue.$func(\key($list), $value);
			}

			return $glued;
		}
		else # no items
		{
			return '';
		}
	}

} # class

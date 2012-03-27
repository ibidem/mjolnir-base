<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Lang
	implements \kohana4\types\Lang
{
	/**
	 * @var string
	 */
	protected static $lang = 'en-us';
	
	/**
	 * Translate a given term. The translation may not necesarily be from one
	 * language to another. For example, grammer use:
	 * 
	 *     Lang::tr(':num people visited London.', ... );
	 *     // 1 => 1 person visited London.
	 *     // 2 => 2 people visited London.
	 *     // 0 => Nobody visited London.
	 * 
	 * If a term is not defined, it will be returned as-is.
	 * 
	 * [!!] Keys and terms are seperate entities.
	 * 
	 * @param string term
	 * @param array addins
	 * @param string source language
	 * @return string
	 */
	public static function tr($term, array $addins = null, $lang = 'en-us')
	{
		$config = \app\CFS::config('lang/'.static::$lang.'/'.$lang);
		if ( ! isset($config['terms'][$term]))
		{
			return $addins ? \strtr($term, $addins) : $term;
		}
		else if ($addins !== null)
		{
			// if we have addins, the term matches to a lambda
			return $config['terms'][$term]($addins);
		}
		else # no addins
		{
			// if there are no addins, the key maps to a string
			return $config['terms'][$term];
		}
	}
	
	/**
	 * Access a specific message identified by the key. 
	 * 
	 * The key MUST be defined.
	 * 
	 * [!!] Keys and terms are seperate entities.
	 * 
	 * @param string key
	 * @param string source language
	 * @return string
	 */
	public static function msg($key, array $addins = null, $lang = 'en-us')
	{
		$config = \app\CFS::config('lang/'.static::$lang.'/'.$lang);
		if ($addins !== null)
		{
			// if we have addins, the term matches to a lambda
			return $config['messages'][$key]($addins);
		}
		else # no addins
		{
			// if there are no addins, the key maps to a string
			return $config['messages'][$key];
		}
	}
			
	/**
	 * @param string target lang
	 */
	public static function lang($lang)
	{
		static::$lang = $lang;
	}
	
	/**
	 * @return string current target language
	 */
	public static function get_lang()
	{
		return static::$lang;
	}
	
} # class

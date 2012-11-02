<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Lang
	implements \mjolnir\types\Lang
{
	/**
	 * @var string
	 */
	private static $current_lang = 'en-us';

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
	static function tr($term, array $addins = null, $from_lang = 'en-us')
	{
		// lang/en-us/messages => translate to en-us using messages
		// lang/en-us/ro-ro => translate to en-us from ro-ro
		// lang/ro-ro/messages
		// lang/ro-ro/en-us

		$config = \app\CFS::config('lang/'.self::$current_lang.'/'.$from_lang);
		if ( ! isset($config[$term]))
		{
			$target_lang = self::$current_lang;
			if ($addins)
			{
				\mjolnir\masterlog
					(
						'Failure',
						"The term [$term] is missing a translation ($from_lang => {$target_lang}).",
						'lang/'.$from_lang.'/'.self::$current_lang.'/'
					);

				return \strtr($term, $addins);
			}
			else if ($from_lang === self::$current_lang)
			{
				return $term;
			}
			else # term not set
			{
				\mjolnir\masterlog
					(
						'Failure',
						"The term [$term] is missing a translation ($from_lang => {$target_lang}).",
						'lang/'.$from_lang.'/'.self::$current_lang.'/'
					);

				return $term;
			}
		}
		else if ($addins !== null)
		{
			// if we have addins, the term matches to a lambda
			return $config[$term]($addins);
		}
		else # no addins
		{
			// if there are no addins, the key maps to a string
			return $config[$term];
		}
	}

	/**
	 * Get's a language file from the current language's directory.
	 *
	 * @return array
	 */
	static function file($file)
	{
		return \app\CFS::config('lang/'.self::$current_lang.'/'.$file);
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
	static function msg($key, array $addins = null)
	{
		$config = \app\CFS::config('lang/'.self::$current_lang.'/messages');

		if ( ! isset($config[$key]))
		{
			throw new \app\Exception('Missing message ['.$key.'] for language ['.self::$current_lang.'].');
		}

		if ($addins !== null)
		{
			// if we have addins, the term matches to a lambda
			return $config[$key]($addins);
		}
		else # no addins
		{
			// if there are no addins, the key maps to a string
			return $config[$key];
		}
	}

	/**
	 * @param string target lang
	 */
	static function lang($lang)
	{
		self::$current_lang = $lang;
	}

	/**
	 * @return string current target language
	 */
	static function get_lang()
	{
		return self::$current_lang;
	}

} # class

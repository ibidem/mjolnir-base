<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Text
{
	/**
	 * @return string
	 */
	static function summary($source, $maxlength, $cutoff_text = '...')
	{
		if (\strlen($source) <= $maxlength)
		{
			return $source;
		}
		else # source > $maxlength
		{
			return \substr($source, 0, $maxlength - \strlen($cutoff_text)).$cutoff_text;
		}
	}
	
	/**
	 * Uniforms indentation. Useful when printing SQL to the console.
	 * 
	 * @return string
	 */
	static function baseindent($source, $indent = '', $uniform_tabs = 4, $ignore_zero_indent = true)
	{
		// unify tabs
		if ($uniform_tabs !== null)
		{
			$source = \str_replace("\t", \str_repeat(' ', $uniform_tabs), $source);
		}
		
		// split into lines
		$lines = \explode("\n", $source);
		
		// detect indent level
		$min_length = null;
		foreach ($lines as $line)
		{
			if (\preg_match('#^([ \t]+)([^ ])#', $line, $matches))
			{
				if ($min_length === null)
				{
					$min_length = \strlen($matches[1]);
				}
				else if (\strlen($matches[1]) < $min_length)
				{
					$min_length = \strlen($matches[1]);
				}
			}
			else if ( ! $ignore_zero_indent)
			{
				$min_length = 0;
				break;
			}
		}
		
		// unify
		foreach ($lines as & $line)
		{
			if (\preg_match('#^[ \t].*#', $line))
			{
				$line = $indent.\substr($line, $min_length);
			}
			else # zero line
			{
				$line = $indent.$line;
			}
		}
		
		return \implode("\n", $lines);
	}

	/**
	 * @return string
	 */
	static function breaks_to_html($source)
	{
		// normalize line breaks
		$source = \str_replace("\r\n", "\n", $source);
		$source = \str_replace("\r", "\n", $source);
		
		return \str_replace("\n", '<br/>', $source);
	}
	
	/**
	 * Padds the source string on the left side with the given filler until it 
	 * reaches the desired size.
	 * 
	 * @return string
	 */
	static function lpad($size, $source, $filler)
	{
		$source = ''.$source;
		$multiplier = $size - \strlen($source);
		return \str_repeat($filler, $multiplier).$source;
	}
	
	/**
	 * Given a value in bytes convert it to more readable MB, GB, etc notation.
	 * 
	 * @return string
	 */
	static function prettybytes($bytes, $decimals = 2)
	{
		$bytes = \intval($bytes);
		
		if ($bytes < 1024) 
		{
			return $bytes .' B';
		} 
		elseif ($bytes < 1048576) 
		{
			return \round($bytes / 1024, $decimals) .' KiB';
		} 
		elseif ($bytes < 1073741824) 
		{
			return \round($bytes / 1048576, $decimals) . ' MiB';
		} 
		elseif ($bytes < 1099511627776) 
		{
			return \round($bytes / 1073741824, $decimals) . ' GiB';
		} 
		elseif ($bytes < 1125899906842624) 
		{
			return \round($bytes / 1099511627776, $decimals) .' TiB';
		} 
		elseif ($bytes < 1152921504606846976) 
		{
			return \round($bytes / 1125899906842624, $decimals) .' PiB';
		} 
		elseif ($bytes < 1180591620717411303424) 
		{
			return \round($bytes / 1152921504606846976, $decimals) .' EiB';
		} 
		elseif ($bytes < 1208925819614629174706176) 
		{
			return \round($bytes / 1180591620717411303424, $decimals) .' ZiB';
		} 
		else # show as multiple of largest
		{
			return \round($bytes / 1208925819614629174706176, $decimals) .' YiB';
		}
	}
	
	/**
	 * @return string
	 */
	static function camelcase_from_dashcase($dashcase)
	{
		return \app\Arr::implode('', \explode('-', $dashcase), function ($k, $segment) {
			return \ucfirst($segment);
		});
	}

} # class

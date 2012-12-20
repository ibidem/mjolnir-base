<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Text extends \app\Instantiatable
{
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

	static function breaks_to_html($source)
	{
		// normalize line breaks
		$source = \str_replace("\r\n", "\n", $source);
		$source = \str_replace("\r", "\n", $source);
		
		return \str_replace("\n", '<br/>', $source);
	}
	
} # class

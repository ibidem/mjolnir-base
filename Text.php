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

} # class

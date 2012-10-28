<?php namespace app;

// The power of functions should be used sparengly and only for really
// convenient aliases to classes; because classes can be overwritten, while
// functions not-very-easily.

if ( ! \function_exists('\mjolnir\expectes'))
{
	function expects($value)
	{
		return \app\Assert::instance($value);
	}
}
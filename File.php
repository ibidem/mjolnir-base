<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class File extends \app\Instantiatable
{
	/**
	 * Write contents to a file.
	 */
	static function puts($file, $contents, $permissions = null)
	{
		if ($permissions === null)
		{
			$permissions = \app\CFS::config('mjolnir/base')['default.file.permissions'];
		}

		\file_put_contents($file, $contents);
		\chmod($file, $permissions);
	}

} # class

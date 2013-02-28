<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class View extends \app\Instantiatable implements \mjolnir\types\ViewFile
{
	use \app\Trait_ViewFile;

	/**
	 * @see \mjolnir\types\Instantiatable
	 * @return static
	 */
	static function instance($file = null, $ext = EXT)
	{
		$instance = parent::instance();

		$file === null or $instance->file_is($file, $ext);

		return $instance;
	}

	/**
	 * @return static $this
	 */
	function file_is($file, $ext = EXT)
	{
		$file = 'views'.DIRECTORY_SEPARATOR.$file;
		$file_path = \app\CFS::file($file, $ext);

		if ($file_path !== null) # found file
		{
			$this->file_path($file_path);
		}
		else # file not found
		{
			throw new \app\Exception("File not found: $file");
		}

		return $this;
	}

} # class

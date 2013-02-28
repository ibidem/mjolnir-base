<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class VideoConverter
{
	/**
	 * Given a source file converts it to an output file.
	 */
	static function convert($source_file, $output_file)
	{
		$driver = '\app\VideoConverter_'.\app\CFS::config('mjolnir/video-converter')['driver'];
		return $driver::instance()->convert($source_file, $output_file);
	}

} # class

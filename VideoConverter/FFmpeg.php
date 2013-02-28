<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   VideoConverter
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class VideoConverter_FFmpeg extends \app\Instantiatable implements \mjolnir\types\VideoConverter
{
	use \app\Trait_VideoConverter;

	/**
	 * Given a source file converts it to an output file.
	 */
	function convert($source_file, $output_file, array $config = null)
	{
		$defaults = \app\CFS::config('mjolnir/video-converter')['FFmpeg.driver'];
		
		if ($config === null)
		{
			$config = $defaults;
		}
		else # 
		{
			$config = \app\Arr::merge($defaults, )
		}
		
	}
	
} # class

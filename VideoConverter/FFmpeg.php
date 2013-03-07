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
		if ( ! \file_exists($source_file) || !\file_exists($output_file))
		{
			return;
		}
		
		if ($config === null)
		{
			$config = \app\CFS::config('mjolnir/video-converter')['FFmpeg.driver'];;
		}
		
		// get file types (simple extention scan)
		
		$matches = null;
		if (\preg_match('#\.[^\.]$#', $source_file, $matches))
		{
			$source_ext = $matches[1];
		}
		else # no extention
		{
			$source_ext = null;
		}
		
		if (\preg_match('#\.[^\.]$#', $output_file, $matches))
		{
			$output_ext = $matches[1];
		}
		else # no extention
		{
			$output_ext = null;
		}
		
		$settings = ' ';
		if ($source_ext !== null && $output_ext !== null)
		{
			if (isset($config['settings'], $config['settings'][$source_ext], $config['settings'][$source_ext][$output_ext]))
			{
				$settings .= $config['settings'][$source_ext][$output_ext].' ';
			}
		}
		
		\passthru('ffmpeg -i '.\escapeshellarg($source_file).$settings.\escapeshellarg($output_file));
	}

} # class

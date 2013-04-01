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

	// ------------------------------------------------------------------------
	// Hooks
	
	/**
	 * @return string settings to pass to ffmpeg
	 */
	protected function handle_rotation($rotation)
	{
		if ($rotation == 90)
		{
			// portrait videos taken with a phone will be recorded into landscape mode
			return ' -metadata Rotation="" -vf "transpose=1"'; # rotate 90 clockwise
		}
		else if ($rotation == 180)
		{
			// who exactly wants to take upside down videos?
			return ' -metadata Rotation="" -vf "vflip,hflip"'; # vertical and horizontal flip
		}
		else if ($rotation == 270)
		{
			// portrait videos taken with a phone will be recorded into landscape mode
			return ' -metadata Rotation="" -vf "transpose=3"'; # rotate 90 clockwise
		}
		else # non-90 and non-180 rotation
		{
			// assuming video was recorded wrong; at the time of this writing there
			// is no funky angled screens and the idea of funky angled screens or 
			// funky video players seems too much of anti-UX gimmick
			return ''; 
		}
	}
	
	// ------------------------------------------------------------------------
	// interface: VideoConverter
	
	/**
	 * Given a source file converts it to an output file.
	 */
	function convert($source_file, $output_file, array $config = null)
	{
		if ( ! \file_exists($source_file))
		{
			return;
		}
		
		if ($config === null)
		{
			$config = \app\CFS::config('mjolnir/video-converter')['FFmpeg.driver'];;
		}
		
		// get file types (simple extention scan)
		
		$matches = null;
		if (\preg_match('#\.([^\.]+)$#', $source_file, $matches))
		{
			$source_ext = $matches[1];
		}
		else # no extention
		{
			$source_ext = null;
		}
		
		if (\preg_match('#\.([^\.]+)$#', $output_file, $matches))
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

		#
		# The following is mostly to deal with .mov recorded using iphones.
		#
		# Iphones can handle their mangled non-standard video rotation metadata,
		# so if we don't want the video to look upside down or on it's side to 
		# everyone else we need to correct it. Support is limited, we can do 
		# basic 90, 180, and delegate everything else to the application (if it 
		# ever needs it).
		#
		
		// attempt to get rotation
		$grep = \trim(\app\Shell::cmd('mediainfo '.\escapeshellarg($source_file)));
		$rotation_adjustment = ''; # rotation is 0 or not specified
		if ( ! empty($grep) && \preg_match('/Rotation[^:]+:[^0-9]+(?P<rotation>[0-9]+)/', $grep, $matches))
		{
			$rotation = \intval($matches['rotation']);
			$rotation_adjustment = $this->handle_rotation($rotation);
		}
		
		$cmd = 'ffmpeg -y -i '
			. \escapeshellarg($source_file)
			. $rotation_adjustment
			. $settings
			. ' -map_metadata -1 '
			. \escapeshellarg($output_file);
		
		$return_status = 1;
		\passthru($cmd, $return_status);
		
		if ($return_status !== 0)
		{
			\mjolnir\log('Video', "Failed: $cmd");
		}
	}

} # class

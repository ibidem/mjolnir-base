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
	 * Handle special orientation. 90 and 180 are processed internally.
	 * 
	 * @return string
	 */
	protected function handle_special_orientation($orientation)
	{
		// assuming video was recorded wrong; at the time of this writing there
		// is no funky angled screens and the idea of funky angled screens seems
		// or funky video players seems too much of anti-UX gimmick
		return ''; 
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
		
		// attempt to get orientation
		$grep = \trim(\app\Shell::cmd('mediainfo '.\escapeshellarg($source_file).' | grep Rotation'));
		
		$orientation_adjustment = ''; # orientation is 0 or not specified
		if ( ! empty($grep) && \preg_match('/(?P<orientation>[0-9]/', $grep, $matches))
		{
			$orientation = \intval($matches['orientation']);
			
			#
			# The following is mostly to deal with .mov recorded using iphones.
			#
			# Essentially only iphones can handle their mangled non-standard
			# video rotation metadata, so if we don't want the video to look
			# upside down or on it's side to everyone else we need to correct 
			# it. Support is limited, we can do basic 90, 180, and delegate 
			# everything else to the application (if it ever needs it).
			#	
			
			if ($orientation == 90)
			{
				// portrait videos taken with a phone will be recorded into landscape mode
				$orientation_adjustment = ' -vf "transpose=1"'; # rotate 90 clockwise
			}
			else if ($orientation == 180)
			{
				// who exactly wants to take upside down videos?
				$orientation_adjustment = ' -vf "vflip,hflip"'; # vertical and horizontal flip
			}
			else # non-90 and non-180 orientation
			{
				$orientation_adjustment = $this->handle_special_orientation($orientation);
			}
		}
		
		$cmd = 'ffmpeg -y -i '
			. \escapeshellarg($source_file)
			. $orientation_adjustment
			. $settings
			. \escapeshellarg($output_file);
		
		$return_status = 1;
		\passthru($cmd, $return_status);
		
		if ($return_status !== 0)
		{
			\mjolnir\log('Video', "Failed: $cmd");
		}
	}

} # class

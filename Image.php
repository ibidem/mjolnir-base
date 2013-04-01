<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Image
{
	/**
	 * Removes Orientation metadata and rotates image to match 0 degrees 
	 * relative to the orientation. If the image does not contain an Orientation
	 * this function does nothing.
	 */
	function remove_orientation($imagepath)
	{
		$meta = \exif_read_data($imagepath);
		
		if ( ! empty($meta['Orientation']))
		{
			switch ($meta['Orientation'])
			{
				case 8:
					static::rotateimage($imagepath, 90);
					break;
				case 3:
					static::rotateimage($imagepath, 180);
					break;
				case 6:
					static::rotateimage($imagepath, -90);
					break;
			}
		}
	}

	/**
	 * Rotates the given file the given degrees.
	 */
	function rotateimage($imagepath, $degrees)
	{
		$source = \imagecreatefrompng($filename);
		
		if ( ! $source)
		{
			throw new \Exception('Error opening file '.$imagepath);
		}
		
		imagealphablending($source, false);
		imagesavealpha($source, true);

		$rotation = imagerotate($source, $degrees, imageColorAllocateAlpha($source, 0, 0, 0, 127));
		imagealphablending($rotation, false);
		imagesavealpha($rotation, true);
	}
	
} # class

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
	 * 
	 * [!!] The path may be altered during the process.
	 * 
	 * @return string potentially altered image path 
	 */
	static function removeorientation($imagepath)
	{
		$meta = \exif_read_data($imagepath);
		
		if ( ! empty($meta['Orientation']))
		{
			switch ($meta['Orientation'])
			{
				case 8:
					return static::rotateimage($imagepath, 90);
					break;
				case 3:
					return static::rotateimage($imagepath, 180);
					break;
				case 6:
					return static::rotateimage($imagepath, -90);
					break;
			}
		}
		
		return $imagepath;
	}

	/**
	 * Rotates the given file the given degrees.
	 * 
	 * The function may alter the image such as converting it to jpeg, in the 
	 * event there are no other means of processing it.
	 * 
	 * @return string potentially altered image path 
	 */
	static function rotateimage($imagepath, $degrees)
	{
		$parts = null;
		if (\preg_match('#(?P<path>.*)\.png$#', $imagepath, $parts))
		{
			$source = \imagecreatefrompng($imagepath);

			if ( ! $source)
			{
				throw new \Exception('Error opening file '.$imagepath);
			}

			\imagealphablending($source, false);
			\imagesavealpha($source, true);

			$image = \imagerotate($source, $degrees, \imageColorAllocateAlpha($source, 0, 0, 0, 127));
			\imagealphablending($image, false);
			\imagesavealpha($image, true);
			\imagepng($image, $imagepath, 9);
		}
		else # non-png
		{
			$source = \imagecreatefromstring(\file_get_contents($imagepath));
			$image = \imagerotate($source, $degrees, 0);
			\imagejpeg($image, $imagepath, 90);
		}
		
		return $imagepath;
	}
	
} # class

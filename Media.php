<?php namespace mjolnir\base;

/**
 * General purpose image library.
 * 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Media
{
	/**
	 * Generates a image handler. A handler is a function that accepts width and
	 * height and outputs the correct url for the given dimentions, or the full
	 * image on null and null if the original image was null itself.
	 * 
	 * @return callable
	 */
	static function image($image)
	{
		$baseconfig = \app\CFS::config('mjolnir/base');
						
		return function ($width = null, $height = null) use ($baseconfig, $image)
			{
				if ($image === null || empty($image))
				{
					return null;
				}
				else if ($width == null || $height == null)
				{
					return $image;
				}
				else # image is not null
				{
					return \app\URL::href
						(
							'mjolnir:thumbnail.route',
							[
								'image' => $baseconfig['path'].$image,
								'width' => $width,
								'height' => $height
							]
						);
				}
			};
	}

} # class

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
			
		if ($image === null || empty($image))
		{
			return function ($width = null, $height = null) { return null; };
		}
		else # image is not empty
		{
			return function ($width = null, $height = null) use ($baseconfig, $image)
				{
					if ($width == null || $height == null)
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
	}
	
	/**
	 * Generates a video handler. A handler is a function that accepts width and
	 * height and outputs the correct markup for the given dimentions, or the 
	 * raw key if width is set to false, ie. $video['videokey'](false)
	 * 
	 * If the key was empty a handler that returns null will be returned.
	 * 
	 * The handler must be created via a video key which is the video url with
	 * out the format.
	 * 
	 * @return callable
	 */
	static function video($videokey)
	{
		if ($videokey === null || empty($videokey))
		{
			return function ($width = null, $height = null) { return null; };
		}
		else # videokey is not empty
		{
			return function ($width = null, $height = null) use ($videokey)
				{
					if ($width === false)
					{
						return $videokey; # intentionally returning raw video code
					}
					else # image is not null
					{
						// use video.formats from uploads
						$uploads = \app\CFS::config('mjolnir/uploads');
						
						// the html module may not be included; so we need to 
						// check if the configuration is usable
						if (empty($uploads))
						{
							$formats = array
								(
									'mp4' => 'video/mp4', 
									'ogv' => 'video/ogg', 
									'webm' => 'video/webm' 
								);
						}
						else # found usable configuration
						{
							$formats = $uploads['video.formats'];
							
							// filter video formats
							$formats = \app\Arr::filter
								(
									$formats, 
									function ($i, $value) 
									{
										return $value !== null;
									}
								);
						}
						
						return \app\View::instance('mjolnir/base/video.template')
							->pass('videokey', $videokey)
							->pass('formats', $formats)
							->pass('width', $width)
							->pass('height', $height)
							->render();
					}
				};
		}
	}
	
	/**
	 * @return string embed code
	 */
	static function embed($identifier, $provider, $width = null, $height = null, $meta = null)
	{
		$embeds = \app\CFS::config('mjolnir/embeds');
		
		if (isset($embeds[$provider]))
		{
			return $embeds[$provider]($identifier, $width, $height, $meta);
		}
		else # no handler
		{
			throw new \Exception('Missing embed handling for provider ['.$provider.'].');
		}
	}
	
	/**
	 * Given embed code parses it into an id and provider.
	 * 
	 * @return array|null
	 */
	static function unwrapembed($embed)
	{
		$result = null;
		$matches = null;
		if (\preg_match('#(src=\"[^0-9]*)?vimeo\.com/(video/)?(?P<id>[0-9]+)([^\"]*\"|$)#', $embed, $matches))
		{
			$result['identifier'] = $matches['id'];
			$result['provider'] = 'vimeo';
		}
		else if (\preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)(?P<id>[^#\&\?]*).*/', $embed, $matches))
		{
			$result['identifier'] = $matches['id'];
			$result['provider'] = 'youtube';
		}
		
		return $result;
	}

} # class

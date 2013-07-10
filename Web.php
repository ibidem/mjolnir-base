<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Web
{
	/**
	 * @return string content at specified url
	 */
	static function get($url)
	{
		// create curl resource 
        $ch = \curl_init();

        // set url 
        \curl_setopt($ch, CURLOPT_URL, $url);

        // return the transfer as a string 
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string 
        $output = \curl_exec($ch);

        // close curl resource to free up system resources 
        \curl_close($ch);
		
		return $output;
	}
	
	/**
	 * Like get only expects json content and decodes it before returning it.
	 * 
	 * @return array parsed json
	 */
	static function getjson($url)
	{
		return \json_decode(static::get($url), true);
	}

} # class

<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Server
{
	/**
	 * @return string
	 */
	static function request_method()
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			// Use the server request method
			return \strtoupper($_SERVER['REQUEST_METHOD']);
		}
		else # REQUEST_METHOD not set
		{
			// Default to GET requests
			return \strtoupper(\mjolnir\types\HTTP::GET);
		}
	}
	
	/**
	 * @return string 
	 */
	static function client_ip()
	{
		if 
		(
			isset($_SERVER['HTTP_X_FORWARDED_FOR'])
			&& isset($_SERVER['REMOTE_ADDR'])
			&& \in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost', 'localhost.localdomain'))
		)
		{
			// Use the forwarded IP address, typically set when the
			// client is using a proxy server.
			// Format: "X-Forwarded-For: client1, proxy1, proxy2"
			$client_ips = \explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			return \array_shift($client_ips);
		}
		elseif 
		(
			isset($_SERVER['HTTP_CLIENT_IP'])
			&& isset($_SERVER['REMOTE_ADDR'])
			&& \in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'localhost', 'localhost.localdomain'))
		)
		{
			// use the forwarded IP address, typically set when the
			// client is using a proxy server.
			$client_ips = \explode(',', $_SERVER['HTTP_CLIENT_IP']);

			return \array_shift($client_ips);
		}
		elseif (isset($_SERVER['REMOTE_ADDR']))
		{
			// the remote IP address
			return $_SERVER['REMOTE_ADDR'];
		}
		
		return '0.0.0.0';
	}
	
	/**
	 * @param string url
	 * @param int 303 (see other), 301 (permament), 307 (temporary)
	 */
	static function redirect($url, $type = 303)
	{
		if ($type === 303)
		{
			\app\GlobalEvent::fire('http:status', 'HTTP/1.1 303 See Other');
		}
		else if ($type == 301)
		{
			\app\GlobalEvent::fire('http:status', 'HTTP/1.1 301 Moved Permanently');
		}
		else if ($type == 307)
		{
			\app\GlobalEvent::fire('http:status', 'HTTP/1.1 307 Temporary Redirect');
		}
		
		// redirect to...
		\app\GlobalEvent::fire('http:attributes', [ 'location' => $url ]);
		
		// perform the redirect
		\app\GlobalEvent::fire('http:send-headers');
		
		exit;
	}

} # class

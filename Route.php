<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Route
{
	static function check_all()
	{
		$routes = \app\CFS::config('routes');
		
		// format: [ key, regex, allowed methods ]
		foreach ($routes as $pattern => $route_info)
		{
			$pattern = \trim($pattern, '/');
			
			if ( ! isset($route_info[1]))
			{
				$regex_pattern = [];
			}
			else # got regex
			{
				$regex_pattern = $route_info[1];
			}
			
			// create route pattern
			$matcher = \app\Route_Pattern::instance()->standard($pattern, $regex_pattern);
			
			if ($matcher->check())
			{
				// retrieve the allowed methods
				if ( ! isset($route_info[3]))
				{
					$methods = ['GET'];
				}
				else if (\is_string($route_info[3]))
				{
					$methods = [$route_info[3]];
				}
				else # is set and is not string; assume array
				{
					$methods = \array_map(\strtoupper, $route_info[3]);
				}
				
				// attempt match
				foreach ($methods as $method)
				{
					if (\app\Server::request_method() === $method)
					{
						// retrieve format
						$format = 'html';
						if (\preg_match('#.*\.(?<format>[a-z0-9-_]+)$#', $route_info[0], $matches))
						{
							$format = $matches['format'];
						}
						
						// retrieve format stack
						$route_stacks = \app\CFS::config('ibidem/route-stacks');
						
						// build faux relay object
						$default_action = 'action_index';
						if ($format !== 'html')
						{
							$default_action = $format.'_index';
						}
						$relay = array
							(
								'matcher' => $matcher,
								'controller' => '\app\Controller_'.\ucfirst($route_info[0]),
								'action' => $default_action
							);
						
						// execute
						$route_stacks[$format]($relay, $route_info[0]);
						exit;
					}
				}
			}
		}
	}
	
	static function matcher($key)
	{
		$routes = \app\CFS::config('routes');
		
		foreach ($routes as $pattern => $route_info)
		{
			if ($route_info[0] === $key)
			{
				$pattern = \trim($pattern, '/');

				if ( ! isset($route_info[1]))
				{
					$regex_pattern = [];
				}
				else # got regex
				{
					$regex_pattern = $route_info[1];
				}

				// create route pattern
				return \app\Route_Pattern::instance()->standard($pattern, $regex_pattern);
			}
		}
		
		return null;
	}

} # class

<?php namespace ibidem\base;

/**
 * The purpose of this class is to store some of the grunt work that would 
 * otherwise go into public files, etc.
 * 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Mjolnir
{
	/**
	 * Shorthand.
	 * 
	 * Runs standard http procedures.
	 */
	static function www($system_config)
	{
		// downtime?
		if ($system_config['maintanence']['enabled'] && ( ! isset($_GET['passcode']) || $_GET['passcode'] !== $system_config['maintanence']['passcode']))
		{
			require 'downtime.php';
			exit;
		}

		// set language
		\app\Lang::lang($system_config['lang']);
		
		// check all routes
		\app\Route::check_all();
		
		// go though all relays
		\app\Relay::check_all();

		// we failed relays
		\header("HTTP/1.0 404 Not Found");
		echo '404 - Not Found';
		exit(1);
	}

	/**
	 * Shorthand.
	 * 
	 * Runs standard theme procedures.
	 */
	static function themes($system_config)
	{
		$stack = function ($relay, $target)
			{
				\app\Layer::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_Theme::instance()
							->relay_config($relay)
					);
			};

		\app\Relay::process('\ibidem\theme\Layer_Theme::style', $stack);
		\app\Relay::process('\ibidem\theme\Layer_Theme::script-map', $stack);
		\app\Relay::process('\ibidem\theme\Layer_Theme::script-src', $stack);
		\app\Relay::process('\ibidem\theme\Layer_Theme::script', $stack);
		\app\Relay::process('\ibidem\theme\Layer_Theme::resource', $stack);

		// we failed relays
		\header("HTTP/1.0 404 Not Found");
		echo '404 - Media Not Found';
		exit(1);
	}
	
	/**
	 * Shorthand.
	 * 
	 * Runs standard command line utility.
	 */
	static function overlord()
	{
		// running on a the command line?
		if (\php_sapi_name() === 'cli')
		{
			Layer::stack
				(
					Layer_TaskRunner::instance()
						->writer(Writer_Console::instance())
						->args($_SERVER['argv'])
				);
		}
	}
	
} # class

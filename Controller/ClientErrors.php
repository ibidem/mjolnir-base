<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Controller
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_ClientErrors extends \app\Controller
{
	function action_log()
	{
		if (\app\Server::request_method() === 'POST')
		{
			if (isset($_POST['url'], $_POST['line'], $_POST['message']))
			{
				// cleanup url
				$base_config = \app\CFS::config('mjolnir/base');
				$url = $_POST['url'];
				$url = \str_replace('http://'.$base_config['domain'].$base_config['path'], '', $url);
				$url = \preg_replace('#^media/themes/[^0-9]+/[0-9\.]+(-complete)?/#', '', $url);
				
				$error_diagnostic = $_POST['message'].' ('.$_POST['location'].' -- '.$url.' @ Ln '.$_POST['line'].')';
				\mjolnir\shortlog('Client', $error_diagnostic, 'client/');
				
				if ($_POST['trace'])
				{
					// clean trace
					$trace = \preg_replace('#(http(s)?:)?//'.$base_config['domain'].$base_config['path'].'media/themes/[^0-9]+/[0-9\.]+(-complete)?/#', '', $_POST['trace']);
					$trace = \preg_replace('#(http(s)?:)?//'.$base_config['domain'].$base_config['path'].'#', '', $trace);
					
					\mjolnir\masterlog('Client', $error_diagnostic."\n\t\t".\str_replace("\n", "\n\t\t", $trace)."\n", 'client/');
				}
				else # no trace
				{
					\mjolnir\masterlog('Client', $error_diagnostic, 'client/');
				}
				
			}
			else # unknown format
			{
				\mjolnir\log('Client', 'Unknown format. POST: '.\serialize($_POST), 'client/');
			}
		}
		else # logging
		{
			\mjolnir\log('Client', 'Recieved unconventional logging request from '.\app\Server::client_ip(), 'client/');
		}
	}

} # class

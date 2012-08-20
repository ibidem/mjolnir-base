<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Task
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Behat extends \app\Task
{
	/**
	 * Execute task.
	 */
	function execute()
	{
		// verify behat is present
		$composer_config = \json_decode(\file_get_contents(DOCROOT.'composer.json'), true);
		$bin_dir = \trim($composer_config['config']['bin-dir'], '/');
		$behat_cmd = DOCROOT.$bin_dir.DIRECTORY_SEPARATOR.'behat';
		if ( ! \file_exists($behat_cmd))
		{
			$this->writer->error('Missing behat runner.')->eol();
			$this->writer->status('Help', 'Please verify you have behat in your composer file.')->eol();
			exit(1);
		}
		
		$paths = \app\CFS::paths();
		
		foreach ($paths as $path)
		{			
			$dir_iterator = new \RecursiveDirectoryIterator($path);
			
			$iterator = new \RecursiveIteratorIterator
				(
					$dir_iterator, 
					\RecursiveIteratorIterator::SELF_FIRST
				);

			foreach ($iterator as $file) 
			{
				if (\preg_match('#behat.yaml$#', $file))
				{
					$pretty_location = \str_replace
						(
							DIRECTORY_SEPARATOR
								. \ibidem\cfs\CFSCompatible::APPDIR
								. DIRECTORY_SEPARATOR
							, 
							'', 
							\str_replace(DOCROOT, '', $path)
						); 
					
					$this->writer->write('Processing feature for '.$pretty_location)->eol()->eol();
					passthru($behat_cmd.' --config='.$file);
				}
			}

		}
	}

} # class

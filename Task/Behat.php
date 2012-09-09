<?php namespace mjolnir\base;

/**
 * @package    mjolnir
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
	function execute($behat_flags = '')
	{		
		$feature = $this->config['feature'];
		
		if ($_SERVER['argc'] > 2)
		{
			$args = $_SERVER['argv'];
			\array_shift($args);
			\array_shift($args);
			
			if ($feature !== false)
			{
				\array_shift($args);
				\array_shift($args);	
			}
			
			$behat_flags .= \app\Collection::implode(' ', $args, function ($i, $v) {
				// is this a flag?
				if ( ! \preg_match('#^-[a-z-]+$#', $v))
				{
					// assume "literal value" and pass on as such
					return '\''.\addcslashes($v, '\'\\').'\'';
				}
				else # it's a flag; probably
				{
					return $v;
				}
			});
		}

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
								. \mjolnir\cfs\CFSCompatible::APPDIR
								. DIRECTORY_SEPARATOR
							, 
							'', 
							\str_replace(DOCROOT, '', $path)
						);
					
					$the_feature = \preg_replace('#^.*[/\\\]features[/\\\]#', '', \dirname($file));
					
					if ($feature !== false && ! \preg_match('#'.\strtolower($feature).'#', \strtolower($the_feature)))
					{
						continue;
					}
					
					$this->writer->eol()->header('Processing feature "'.$the_feature.'" for '.$pretty_location);
					$executed_cmd = $behat_cmd.' '.$behat_flags.' --config="'.$file.'"';
					passthru($executed_cmd);
				}
			}

		}
	}

} # class

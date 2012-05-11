<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Honeypot extends \app\Task
{
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$ns = $this->config['namespace'];
		$modules = \array_flip(\app\CFS::get_modules());
		if (isset($modules[$ns]))
		{
			$path = $modules[$ns];
			$files = static::files($path);
			$output = '<?php namespace app;';
			$output .= PHP_EOL.PHP_EOL.'// This is a IDE honeypot. :)';
			$output .= PHP_EOL.PHP_EOL.'// HowTo: minion honeypot -n "'.
					\str_replace('\\', '\\\\', $this->config['namespace']).'"'.
					PHP_EOL.PHP_EOL;
			
			foreach ($files as $file)
			{
				if (\preg_match('#^Trait_#', $file))
				{
					$output .= 'trait '.$file.' { use \\'.$ns.'\\'.$file.'; }'.PHP_EOL;
				}
				else if ( ! \preg_match('#^Enum_#', $file)) # not a internal enum
				{
					if (\method_exists('\\'.$ns.'\\'.$file,'instance'))
					{
						$output .= 'class '.$file.' extends \\'.$ns.'\\'.$file.' { /** @return \\'.$ns.'\\'.$file.' */ static function instance() { return parent::instance(); } }'.PHP_EOL;
					}
					else # class is not instantitable
					{
						$output .= 'class '.$file.' extends \\'.$ns.'\\'.$file.' {}'.PHP_EOL;
					}
						
				}
			}
			$dir = $path.DIRECTORY_SEPARATOR.\app\CFS::APPDIR.DIRECTORY_SEPARATOR;
			if ( ! \is_dir($dir))
			{
				\mkdir($dir, 0777, true);
			}
			\file_put_contents($dir.'honeypot'.EXT, $output);
			$this->writer
				->status
					(
						'Info', 
						'Succesfully created ['.\str_replace(DOCROOT, '', $dir.'honeypot'.EXT).']'
					)
				->eol();
		}
		else # namespace does not exist
		{
			throw new \app\Exception_NotFound
				('No such namespace registered; please check your [environment.php] file.');
		}
	}
	
	protected static function files($path, $prefix = '')
	{
		static $exclude_pattern = array('..', '.', \app\CFS::APPDIR);
		
		$ext_pattern = '#'.\str_replace('.', '\.', EXT).'$#';
		$files = \array_diff(\scandir($path), $exclude_pattern);
		$clean_files = array();
		foreach ($files as $file)
		{
			if (\is_dir($path.DIRECTORY_SEPARATOR.$file))
			{
				$clean_files = \array_merge
					(
						$clean_files, 
						static::files($path.DIRECTORY_SEPARATOR.$file, $prefix.$file.'_')
					);
			}
			else if (\preg_match($ext_pattern, $file))
			{
				// found a class file
				$clean_files[] = $prefix.\preg_replace($ext_pattern, '', $file);
			}
		}
		
		return $clean_files;
	}
	
} # class
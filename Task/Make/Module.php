<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Module extends \app\Task
{
	/**
	 * @param string module name
	 * @return string version file contents
	 */
	protected function version_file($name)
	{
		return 
			  '<?php return array'.PHP_EOL
			. "\t(".PHP_EOL
			. "\t\t'$name' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t'major' => '1',".PHP_EOL
			. "\t\t\t\t'minor' => '0',".PHP_EOL
			. "\t\t\t)".PHP_EOL
			. "\t);".PHP_EOL
			;
	}
	
	/**
	 * @return string language file readme
	 */
	protected function lang_readme_file()
	{
		return 
			  'Format Help'.PHP_EOL
			. '==========='.PHP_EOL
			. PHP_EOL
			. 'The format for lang directories is:'.PHP_EOL
			. PHP_EOL
			. '<pre>lang/source_lang/target_lang.php</pre>'.PHP_EOL
			. PHP_EOL
			;	
	}
	
	/**
	 * @return string language file readme
	 */
	protected function honeypot_file($namespace)
	{
		$namespace = \str_replace('\\', '\\\\', $namespace);
		
		return 
			  '<?php namespace app;'.PHP_EOL
			. PHP_EOL
			. '// This is a IDE honeypot. :)'.PHP_EOL
			. PHP_EOL
			. '// HowTo: minion honeypot -n "'.$namespace.'"'
			. PHP_EOL
			;
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$name = $this->config['name'];
		$namespace = $this->config['namespace'];
		$forced = $this->config['forced'];
		
		// module exists?
		$module_name = MODPATH.$name;
		if (\file_exists($module_name) && ! $forced)
		{
			$this->writer
				->error("The module [$module_name] already exists.")->eol()
				->status('Help', 'Use --forced to overwrite existsing files.')->eol();
			return;
		}
		
		$ds = DIRECTORY_SEPARATOR;
		
		// create the directory structure
		$dir = $module_name;
		\file_exists($dir) or \mkdir($dir, 0777, true); # base dir
		$app_dir = $dir.$ds.\ibidem\cfs\CFSCompatible::APPDIR;
		\file_exists($app_dir) or \mkdir($app_dir, 0777, true); # App dir
		$config_dir = $app_dir.$ds.\ibidem\cfs\CFSCompatible::CNFDIR;
		\file_exists($config_dir) or \mkdir($config_dir, 0777, true); # App/config
		$sql_dir = $config_dir.$ds.'sql';
		\file_exists($sql_dir) or \mkdir($sql_dir, 0777, true); # App/config/sql
		$lang_dir = $config_dir.$ds.'lang';
		\file_exists($lang_dir) or \mkdir($lang_dir, 0777, true); # App/config/lang
		$test_dir = $app_dir.$ds.'tests';
		\file_exists($test_dir) or \mkdir($test_dir, 0777, true); # App/test
		
		// create App/config/version
		\file_put_contents
			(
				$config_dir.$ds.'version'.EXT, 
				static::version_file(\ltrim($namespace, '\\'))
			);
		// create lang/README.md
		\file_put_contents
			(
				$lang_dir.$ds.'README.md', 
				static::lang_readme_file()
			);
		// create honeypot
		\file_put_contents
			(
				$app_dir.$ds.'honeypot'.EXT, 
				static::honeypot_file($namespace)
			);
		// print notice
		$this->writer
			->status('Info', 'Module created!')->eol()
			->status('Help', 'To enable it, in your modules add: MODPATH.\''.$name.'\' => \''.\ltrim($namespace, '\\').'\',')->eol();
	}
	
} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Class extends \app\Task
{
	/**
	 * @param string class name
	 * @param string namespace
	 * @param string category
	 * @param array configuration
	 * @return string 
	 */
	protected function class_file($class_name, $namespace, $category, $config)
	{
		$file = "<?php namespace $namespace;".PHP_EOL.PHP_EOL;
		
		if ($config->disclaimer !== null)
		{
			$file .= '/* '.\wordwrap($config->disclaimer, 77, PHP_EOL.' * ')
				. PHP_EOL.' */'.PHP_EOL.PHP_EOL;
		}
		
		$file .= '/**'.PHP_EOL
			. ' * @package    '.$namespace.PHP_EOL
			. ' * @category   '.$category.PHP_EOL
			. ' * @author     '.$config->author.PHP_EOL
			. ' * @copyright  (c) '.\date('Y').', '.$config->author.PHP_EOL
			;
		
		if ($config->license)
		{
			$file .= ' * @license    '.$config->license.PHP_EOL;
		}
		
		$file .= ' */'.PHP_EOL;
		
		$file .= "class $class_name".PHP_EOL
			. '{'.PHP_EOL
			. "\t// @todo".PHP_EOL
			. PHP_EOL
			. '} # class'.PHP_EOL
			;
		
		return $file;
	}
	
	/**
	 * @param string class name
	 * @param string namespace
	 * @return string
	 */
	protected function test_file($class_name, $namespace, $category, $config)
	{
		$file 
			= "<?php namespace $namespace;".PHP_EOL
			. PHP_EOL
			. '/**'.PHP_EOL
			. ' * @package    '.$namespace.PHP_EOL
			. ' * @category   '.$category.PHP_EOL
			. ' * @author     '.$config->author.PHP_EOL
			. ' * @copyright  (c) '.\date('Y').', '.$config->author.PHP_EOL
			;
		
		if ($config->license)
		{
			$file .= ' * @license    '.$config->license.PHP_EOL;
		}
		
		$file .= ' */'.PHP_EOL;
		
		$file .= 
			  "class {$class_name}Test".PHP_EOL
			. '{'.PHP_EOL
			. "\t// @todo".PHP_EOL
			. PHP_EOL
			. '} # class'.PHP_EOL
			;
		
		return $file;
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$class = $this->config['class'];
		$category = $this->config['category'];
		$forced = $this->config['forced'];
		
		// normalize class
		$class = \ltrim($class, '\\');
		
		// does project file exist?
		if ( ! \file_exists(DOCROOT.'project.json'))
		{
			$this->writer
				->error('The ['.DOCROOT.'project.json'.'] does not exist.')
				->eol();
			
			$this->writer
				->status('Help', 'This module requires author and package. \
				                  Optionally, also disclaimer and license.')
				->eol();
			
			return;
		}
		
		$ns_div = \strrpos($class, '\\');
		// does class have namespace?
		if ($ns_div === false)
		{
			$this->writer
				->error('You must provide fully qualified class name.')->eol();
			return;
		}
		
		$namespace = \substr($class, 0, $ns_div);
		$class_name = \substr($class, $ns_div + 1);
		
		$modules = \app\CFS::get_modules();
		$namespaces = \array_flip($modules);

		// module path exists?
		if ( ! isset($namespaces[$namespace]) || ! \file_exists($namespaces[$namespace]))
		{
			$this->writer
				->error('Module ['.$namespace.'] doesn\'t exist; you can use make:module to create it')->eol();
			return;
		}
		
		$module_path = $namespaces[$namespace];
		$class_div = \strrpos($class_name, '_');
		$class_path = '';
		$class_file = '';
		// has underscore?
		if ($class_div === false)
		{
			$class_path = DIRECTORY_SEPARATOR;
			$class_file = $class_name.EXT;
		}
		else # found div
		{
			$class_path = DIRECTORY_SEPARATOR
				. \str_replace
					(
						'_', # search
						DIRECTORY_SEPARATOR, # replace
						\substr($class_name, 0, $class_div)
					)
				. DIRECTORY_SEPARATOR;
			
			$class_file = \substr($class_name, $class_div + 1).EXT;
		}
		// file exists?
		if ( ! $forced && \file_exists($module_path.$class_path.$class_file))
		{
			$this->writer
				->error('Class exists. Use --forced if you want to overwrite.')
				->eol();
			
			return;
		}
		
		// create path
		$full_path = $module_path.$class_path;
		\file_exists($full_path) or \mkdir($full_path, 0777, true);
		
		$config = \json_decode(\file_get_contents(DOCROOT.'project.json'));
		
		// create class
		\file_put_contents
			(
				$full_path.$class_file, 
				static::class_file($class_name, $namespace, $category, $config)
			);
		
		// notify
		$this->writer->status('Info', 'Class created.')->eol();
		
		// create test
		$test_path = $module_path.DIRECTORY_SEPARATOR
			. \ibidem\cfs\CFSCompatible::APPDIR.DIRECTORY_SEPARATOR.'tests'
			. DIRECTORY_SEPARATOR.\ltrim($class_path, '\\');
		
		\file_exists($test_path) or \mkdir($test_path, 0777, true);

		\file_put_contents
			(
				$test_path.$class_file,
				static::test_file($class_name, $namespace, $category, $config)
			);
		
		// notify
		$this->writer->status('Info', 'Test class created.')->eol();
		
		// update honeypot
		$this->writer->status('Info', 'Updating honeypot...')->eol();
		Task_Honeypot::instance()
			->config(array('namespace' => $namespace))
			->writer($this->writer)
			->execute();
	}
	
} # class

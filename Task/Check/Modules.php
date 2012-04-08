<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Check_Modules extends \app\Task
{
	private function php_binary() 
	{
		$paths = \explode(PATH_SEPARATOR, \getenv('PATH'));
		foreach ($paths as $path) 
		{
			$php_executable = $path . DIRECTORY_SEPARATOR . 'php' . (isset($_SERVER['WINDIR']) ? '.exe' : '');
			if (\file_exists($php_executable) && \is_file($php_executable)) {
				return $php_executable;
			}
		}
		
		return 'not found; try: which php'; // not found
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{
		// PHP_BINARY is avaiable from php5.4+
		if ( ! \defined('PHP_BINARY'))
		{
			\define('PHP_BINARY', self::php_binary());
		}
		
		$this->writer->eol()->write(' Running: '.PHP_BINARY)->eol()->eol();
		
		// if the checking should stop on error
		$no_stop = $this->config['no-stop'];
		$strict = $this->config['strict'];
		// load requirements
		$modules = \app\CFS::config('ibidem/require');
		
		$failed = 0;
		$errors = 0;
		foreach ($modules as $module => $requirements)
		{
			$this->writer->subheader($module);
			foreach ($requirements as $requirement => $tester)
			{
				try
				{
					 $status = $tester();
				}
				catch (\Exception $e)
				{
					$status = \ibidem\types\Enum_Requirement::untestable;
				}
				
				switch ($status)
				{
					case \ibidem\types\Enum_Requirement::untestable:
						$this->writer->status('untestable', $requirement)->eol();
						! $no_stop or self::error();
						++$errors;
						break;
					case \ibidem\types\Enum_Requirement::error:
						$this->writer->status('error', $requirement)->eol();
						! $no_stop or self::error();
						++$errors;
						break;
					case \ibidem\types\Enum_Requirement::failed:
						$this->writer->status('failed', $requirement)->eol();
						( ! $strict && ! $no_stop) or self::error();
						++$failed;
						break;
					case \ibidem\types\Enum_Requirement::available:
						$this->writer->status('passed', $requirement)->eol();
						break;
				}
			}
			$this->writer->eol();
		}

		if ($failed + $errors === 0)
		{
			$this->writer->eol()
				->write(' PASSED. Modules running optimally.')->eol();
		}
		else if ($failed > 0 && $errors === 0)
		{
			$this->writer->eol()
				->write(' PASSED. But, '.$failed.' tests failed.')->eol()
				->write(' Failed modules will run using fallbacks.')->eol();
		}
		else if ($errors > 0)
		{
			$this->writer->eol()->write(' FAILED DEPENDENCIES ')->eol();
			exit(1);
		}
	}

	/**
	 * Exit with failed message 
	 */
	private function error()
	{
		$this->writer->eol()->write(' FAILED DEPENDENCIES ')->eol();
		$this->writer->eol()->status('Help', 'Make sure the php you\'re running the command with is the same php the server is using!')->eol();
		exit(1);
	}
	
} # class
<?php namespace ibidem\base;

// Because the ibidem\base doesn't have proper support for database persistence
// the per version migrations is added in when you enable the database module.
// This version will only handle basic install/uninstall which do not require 
// the current version to be stored in the database.

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Migrate extends \app\Task
{
	/**
	 * List versions.
	 */
	protected function list_versions()
	{
		$versions = \array_keys(\app\CFS::config('ibidem/migrations'));
		\sort($versions);
		$this->writer->write(' Migrations: '.\implode(', ', $versions));
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{	
		if ($this->config['list'])
		{
			$this->list_versions();
		}
		else if ($this->config['install'])
		{
			$migrations = \app\CFS::config('ibidem/migrations');
			\ksort($migrations);
			foreach ($migrations as $version => $migrations)
			{
				$this->writer->status('Info', 'Migrations for v'.$version)->eol();
				$this->migrations_up($migrations);
			}
		}
		else if ($this->config['uninstall'])
		{
			$migrations = \app\CFS::config('ibidem/migrations');
			\krsort($migrations);
			foreach ($migrations as $version => $migrations)
			{
				$this->writer->status('Info', 'Migrations for v'.$version)->eol();
				$this->migrations_down($migrations);
			}
		}
		else # unknown, list versions
		{
			$this->list_versions();
		}
	}
	
	/**
	 * @param array migrations
	 */
	protected function migrations_up(array $migrations)
	{
		try
		{
			$constraints = array();
			// perform basic migration
			foreach ($migrations as $migration)
			{
				$this->writer->status('Info', '  up: '.$migration.'... ');
				$constraints[$migration] = $migration::instance()->up();
				$this->writer->write('done.')->eol();
			}
			// bind all constraints
			foreach ($constraints as $migration => $constraint)
			{
				$this->writer->status('Info', 'bind: '.$migration.'... ');
				if ($constraint !== NULL)
				{
					\call_user_func($constraint);
					$this->writer->write('done.')->eol();
				}
				else # null constraint
				{
					$this->writer->write('skipped.')->eol();
				}
			}
		} 
		catch (\Exception $exception)
		{
			$this->writer->write('failed.')->eol();
			throw $exception;
		}
	}
	
	/**
	 * @param array migrations
	 */
	protected function migrations_down(array $migrations)
	{
		try
		{
			$constraints = array();
			// perform basic migration
			foreach ($migrations as $migration)
			{
				$this->writer->status('Info', 'down: '.$migration.'... ');
				$constraints[$migration] = $migration::instance()->down();
				$this->writer->write('done.')->eol();
			}
		} 
		catch (\Exception $exception)
		{
			$this->writer->write('failed.')->eol();
			throw $exception;
		}
	}
	
} # class
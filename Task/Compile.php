<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Task
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Compile extends \app\Task
{
	/**
	 * Execute task.
	 */
	function execute()
	{
		$paths = \app\CFS::paths();
		$env_config = include DOCROOT.'environment'.EXT;

		if (isset($env_config['themes']))
		{
			foreach ($env_config['themes'] as $theme => $path)
			{
				$paths[] = $path;
			}
		}

		$files = \app\CFS::find_files('#^\+compile.rb$#', $paths);

		if (empty($files))
		{
			$this->writer->write(' No [+compile.rb] files detected on the system.')
				->eol()->eol();
		}

		foreach ($files as $file)
		{
			$this->writer->write(' Running: '.$file)->eol()->eol();
			\passthru($file);
			$this->writer->eol()->eol();
		}
	}

} # class

<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Versions extends \app\Task
{
	/**
	 * @var array
	 */
	protected static $defaults = array
		(
			'name' => null, 
			'major' => '0',
			'minor' => '0',
			'hotfix' => '0',
			'tag' => null,
		);
	
	/**
	 * Execute task.
	 */
	function execute()
	{
		$versions = \app\CFS::config('version');
		$length = 10;
		foreach (\array_keys($versions) as $key)
		{
			if ($length < \strlen($key))
			{
				$length = \strlen($key);
			}
		}
		$format = ' %'.$length.'s - %s';
		foreach ($versions as $key => $info)
		{
			$v = \app\CFS::merge(static::$defaults, $info);
			$version = $v['major'].'.'.$v['minor']
				. ($v['hotfix'] !== '0' ? '.'.$v['hotfix'] : '')
				. ($v['tag'] !== null ? '-'.$v['tag'] : '');

			$this->writer->writef($format, $key, $version)->eol();
		}
	}
	
} # class
<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task extends \app\Instantiatable implements \mjolnir\types\Task
{
	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var \mjolnir\types\Writer
	 */
	protected $writer;

	/**
	 * Tasks are of the form class:name:example which should translate to
	 * \app\Task_Class_Name_Example
	 *
	 * @param string encoded task
	 * @return \mjolnir\base\Task
	 */
	static function instance($encoded_task = null)
	{
		if ($encoded_task)
		{
			$class_name = '\app\Task';
			$class_segments = \explode(':', $encoded_task);

			foreach ($class_segments as $segment)
			{
				$class_name .= '_'.\ucfirst($segment);
			}

			return $class_name::instance();
		}
		else # instance
		{
			return parent::instance();
		}
	}

	/**
	 * @param array config
	 * @return \mjolnir\base\Task $this
	 */
	function config(array $config)
	{
		$this->config = $config;
		return $this;
	}

	/**
	 * @param array config
	 * @return \mjolnir\base\Task $this
	 */
	function writer(\mjolnir\types\Writer $writer)
	{
		$this->writer = $writer;
		return $this;
	}

	/**
	 * Execute task.
	 */
	function execute()
	{
		$this->writer->error('Command is not available at this time.')->eol();
		exit(1);
	}

} # class

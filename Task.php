<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task extends \app\Instantiatable
	implements \ibidem\types\Task
{
	/**
	 * @var array 
	 */
	protected $config;
	
	/**
	 * @var \ibidem\types\Writer 
	 */
	protected $writer;
	
	/**
	 * Tasks are of the form class:name:example which should translate to
	 * \app\Task_Class_Name_Example
	 * 
	 * @param string encoded task
	 * @return \ibidem\types\Task
	 */
	public static function instance($encoded_task = null)
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
	 * @return $this
	 */
	public function config(array $config)
	{
		$this->config = $config;
		return $this;
	}
	
	/**
	 * @param array config
	 * @return $this
	 */
	public function writer(\ibidem\types\Writer $writer)
	{
		$this->writer = $writer;
		return $this;
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{
		throw \app\Exception_NotApplicable::instance('Tasks is not available at this time.')
			->title('Not implemented.');
	}
	
} # class

<?php namespace ibidem\base;

/**
 * Syntactic sugar task.
 * 
 * @package    ibidem
 * @category   Task
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Trait extends \app\Task_Make_Class
{
	protected static $filetype = 'trait';
	
	function execute()
	{
		$this->config['class'] = $this->config['trait'];
		$this->config['with-tests'] = false;
		$this->config['category'] = false;
		$this->config['library'] = true;
		
		parent::execute();
	}
	
} # class

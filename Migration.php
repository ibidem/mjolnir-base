<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Migration extends \app\Instantiatable
	implements \kohana4\types\Migration
{
	/**
	 * Sets up constraints and other post-migration tasks.
	 */
	public function bind() 
	{
		// empty
	}
	
	/**
	 * @return array
	 */
	protected function bind_callback()
	{
		return [$this, 'bind'];
	}
	
	/**
	 * Do migration.
	 * 
	 * @return array callback to bind
	 */
	public function up() 
	{
		return $this->bind_callback();
	}
	
	/**
	 * Undo migration.
	 */
	public function down()
	{
		// empty
	}
	
} # class

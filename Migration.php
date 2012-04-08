<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
abstract class Migration extends \app\Instantiatable
	implements \ibidem\types\Migration
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
		return array($this, 'bind');
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

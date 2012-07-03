<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Migration
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Migration_Register extends \app\Migration_Template_MySQL
{
	function bind() 
	{
		// no binding
	}
	
	public function up() 
	{
		$this->createtable
			(
				\app\Register::table(),
				'
					`key`   :title,
					`value` :block,
					
					PRIMARY KEY (`key`)
				'
			);
		
		// populate register
		$key = null;
		$value = null;
		$statement = \app\SQL::prepare
			(
				__METHOD__,
				'
					INSERT INTO `'.\app\Register::table().'`
						(`key`, `value`)
					VALUES
						(:key, :value)
				',
				'mysql'
			)
			->bind(':key', $key)
			->bind(':value', $value);
		
		foreach (\app\CFS::config_file('ibidem/register')['keys'] as $target => $default)
		{
			$key = $target;
			$value = $default;
			$statement->execute();
		}
		
		return parent::up();
	}
	
	public function down() 
	{
		$this->droptables
			(
				[
					\app\Register::table(),
				]
			);
	}

} # class

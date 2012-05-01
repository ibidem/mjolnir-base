<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Migration_Template_MySQL extends \app\Migration
{
	/**
	 * @var array 
	 */
	protected $config;
	
	/**
	 * @param string table (with prefix)
	 * @param array constraints 
	 */
	protected function constraints($table, array $constraints)
	{
		$query = "ALTER TABLE `".$table."` ";
		
		$idx = 0;
		$count = \count($constraints);
		foreach ($constraints as $key => $constraint)
		{
			++$idx;
			$query .= 
				"
					ADD CONSTRAINT `".$table."_ibfk_".$idx."`
					   FOREIGN KEY (`".$key."`) 
					    REFERENCES `".$constraint[0]."` (`id`) 
					     ON DELETE ".$constraint[1]." 
					     ON UPDATE ".$constraint[2]." 
				";
			if ($idx < $count)
			{
				$query .= ', ';
			}
			else # last element
			{
				$query .= ';';
			}
		}
		
		\app\SQL::prepare
			(
				'ibidem/base:migration_template_constraints',
				$query,
				'mysql'
			)
			->execute();
	}
	
	/**
	 * @return \ibidem\base\Migration_Template_MySQL
	 */
	public static function instance()
	{
		$instance = parent::instance();
		
		$instance->config = \app\CFS::config('migration/mysql');
		
		return $instance;
	}
	
	/**
	 * @param string table
	 * @param string fields 
	 */
	protected function createtable($table, $fields)
	{
		\app\SQL::prepare
			(
				'ibidem/base:migration_template_createtable',
				\strtr
				(
					"
						CREATE TABLE IF NOT EXISTS `".$table."` 
						(
							".$fields."
						) 
						ENGINE=:engine  
						DEFAULT CHARSET=:default_charset
					",
					$this->config
				),
				'mysql'
			)
			->execute();
	}
	
	/**
	 * @param array tables 
	 */
	protected function droptables(array $tables)
	{
		$state = 0;
		\app\SQL::prepare
			(
				'ibidem/base:migration_template_droptable_fkcheck',
				"SET foreign_key_checks = :check",
				'mysql'
			)
			->bind_int(':check', $state)
			->execute();
		
		foreach ($tables as $table)
		{
			\app\SQL::prepare
				(
					'ibidem/base:migration_template_droptable_drop',
					"DROP TABLE IF EXISTS `".$table."`",
					'mysql'
				)
				->execute();
		}
		
		$state = 1;
		\app\SQL::prepare
			(
				'ibidem/base:migration_template_droptable_fkcheck',
				"SET foreign_key_checks = :check",
				'mysql'
			)
			->bind_int(':check', $state)
			->execute();
	}
	
} # class

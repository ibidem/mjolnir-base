<?php namespace ibidem\base;

/**
 * Static library that acts as shortcut for running statements on default 
 * database. All statements are esentially equivalent to doing 
 * \app\SQLDatabase::instance() and then calling the equivalent method.
 * 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class SQL
{	
	/**
	 * @param string key
	 * @param string statement
	 * @param string language of statement
	 * @return \ibidem\types\SQLStatement
	 */
	public static function prepare($key, $statement = null, $lang = null)
	{
		return \app\SQLDatabase::instance()->prepare($key, $statement, $lang);
	}
	
	/**
	 * @param string key
	 * @param array values
	 * @param string table
	 * @return array 
	 */
	public static function insert($key, array $values, $table)
	{
		$database = \app\SQLDatabase::instance();
		$keys = \array_keys($values);
		$values = \array_values($values);
		
		$fields = array();
		$idx = 0;
		foreach ($values as $value)
		{
			$fields[] = ':field_'.$idx++;
		}
		
		$statement = static::prepare
			(
				'ibidem/base:SQL_insert',
				'
					INSERT INTO `'.$table.'`
						('.\implode(', ', $keys).')
					VALUES
						('.\implode(', ', $fields).')
				',
				'mysql'
			);
		
		$idx = 0;
		foreach ($values as $value)
		{
			if (\is_int($value))
			{
				$statement->bindInt(':field_'.$idx++, $value);
			}
			else # not int
			{
				$statement->bind(':field_'.$idx++, $value);
			}
		}
		
		return $statement->execute();
	}
	
	/**
	 * Begin transaction.
	 * 
	 * @return \ibidem\types\SQLDatabase
	 */
	public static function begin()
	{
		return \app\SQLDatabase::instance()->begin();
	}
	
	/**
	 * Commit transaction.
	 * 
	 * @return \ibidem\types\SQLDatabase
	 */
	public static function commit()
	{
		return \app\SQLDatabase::instance()->commit();
	}
	
	/**
	 * Rollback transaction.
	 * 
	 * @return \ibidem\types\SQLDatabase
	 */
	public static function rollback()
	{
		return \app\SQLDatabase::instance()->rollback();
	}
	
} # class

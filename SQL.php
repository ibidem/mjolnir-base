<?php namespace kohana4\base;

/**
 * Static library that acts as shortcut for running statements on default 
 * database. All statements are esentially equivalent to doing 
 * \app\SQLDatabase::instance() and then calling the equivalent method.
 * 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class SQL
{	
	/**
	 * @param string key
	 * @param string statement
	 * @param string language of statement
	 * @return \kohana4\types\SQLStatement
	 */
	public static function prepare($key, $statement = null, $lang = null)
	{
		return \app\SQLDatabase::instance()->prepare($key, $statement, $lang);
	}
	
	/**
	 * Begin transaction.
	 * 
	 * @return $this
	 */
	public static function begin()
	{
		return \app\SQLDatabase::instance()->begin();
	}
	
	/**
	 * Commit transaction.
	 * 
	 * @return $this
	 */
	public static function commit()
	{
		return \app\SQLDatabase::instance()->commit();
	}
	
	/**
	 * Rollback transaction.
	 * 
	 * @return $this
	 */
	public static function rollback()
	{
		return \app\SQLDatabase::instance()->rollback();
	}
	
} # class

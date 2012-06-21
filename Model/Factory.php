<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
abstract class Model_Factory
{
	/**
	 * @var int|null 
	 */
	protected static $last_inserted_id;
	
	/**
	 * Reset caches.
	 * 
	 * @param array fields 
	 */
	public static function cache_reset(array $fields)
	{
		// no default cache resets
	}
	
	/**
	 * @return type 
	 */
	final public static function last_inserted_id()
	{
		return self::$last_inserted_id;
	}	
	
	/**
	 * Return validator for fields. Can be used in cases where all errors need
	 * to be reported for a form even though we already know there are some
	 * errors that would prevent assembly.
	 * 
	 * @param array fields
	 * @return \ibidem\types\Validator
	 */
	public static function validator(array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[validator] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * Return validator for fields. Can be used in cases where all errors need
	 * to be reported for a form even though we already know there are some
	 * errors that would prevent assembly.
	 * 
	 * @param array fields
	 * @return \ibidem\types\Validator
	 */
	public static function update_validator($id, array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[update_validator] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * Assemble fields after validation.
	 * 
	 * @param array fields
	 */
	public static function assemble(array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[assemble] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * Assemble fields after validation.
	 * 
	 * @param array fields
	 */
	public static function update_assemble($id, array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[update_assemble] method not implmented in ['.\get_called_class().'].');
	}

	/**
	 * @param int id
	 * @param array fields
	 * @return \app\Validator|null
	 */
	final public static function update($id, array $fields)
	{
		$validator = static::update_validator($id, $fields);
		
		if ($validator->validate() === null)
		{
			static::update_assemble($id, $fields);
		}
		else # invalid
		{
			return $validator;
		}
		
		return null;
	}
	
	/**
	 * Fabricate the model.
	 * 
	 * @param array fields required for creation
	 * @return \ibidem\types\Validator on error, null on success
	 */
	final public static function factory(array $fields)
	{
		$validator = static::validator($fields);
		
		if ($validator === null || $validator->validate() === null)
		{
			static::assemble($fields);
			
			// invalidate caches
			static::cache_reset($fields);
		}
		else # did not pass validation
		{
			return $validator;
		}
		
		return null;
	}
	
	/**
	 * @param array user id's 
	 */
	public static function delete(array $IDs)
	{
		$entry = null;
		$statement = \app\SQL::prepare
			(
				__METHOD__,
				'
					DELETE FROM `'.static::table().'`
					 WHERE id = :id
				',
				'mysql'
			)
			->bind_int(':id', $entry);
		
		foreach ($IDs as $id)
		{
			$entry = $id;
			$statement->execute();
		}
	}
	
	/**
	 * @return array public entry data
	 */
	public static function entry($id)
	{
		throw new \app\Exception_NotApplicable
			('[entry] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * @return array public entry data
	 */
	public static function entries($page, $limit, $offset = 0)
	{
		throw new \app\Exception_NotApplicable
			('[entries] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * Total entry count in table.
	 * 
	 * @return int count
	 */
	public static function count()
	{
		return (int) \app\SQL::prepare
			(
				__METHOD__,
				'
					SELECT COUNT(1)
					  FROM `'.static::table().'`
				',
				'mysql'
			)
			->execute()
			->fetch_array()
			['COUNT(1)'];
	}
	
} # class

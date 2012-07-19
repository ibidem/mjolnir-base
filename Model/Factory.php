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
	static function cache_reset(array $fields)
	{
		// no default cache resets
	}
	
	/**
	 * @return type 
	 */
	final static function last_inserted_id()
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
	static function validator(array $fields)
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
	static function update_validator($id, array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[update_validator] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * Assemble fields after validation.
	 * 
	 * @param array fields
	 */
	static function assemble(array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[assemble] method not implmented in ['.\get_called_class().'].');
	}
	
	/**
	 * Assemble fields after validation.
	 * 
	 * @param array fields
	 */
	static function update_assemble($id, array $fields)
	{
		throw new \app\Exception_NotApplicable
			('[update_assemble] method not implmented in ['.\get_called_class().'].');
	}

	/**
	 * @param int id
	 * @param array fields
	 * @return \app\Validator|null
	 */
	final static function update($id, array $fields)
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
	final static function factory(array $fields)
	{
		$validator = static::validator($fields);
		
		if ($validator === null || $validator->validate() === null)
		{
			static::assemble($fields);
			
			// store last inserted id
			self::$last_inserted_id = \app\SQL::last_inserted_id();
			
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
	static function delete(array $IDs)
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
	static function entry($id)
	{
		return \app\SQL::prepare
			(
				__METHOD__,
				'
					SELECT *
					  FROM `'.static::table().'`
					 WHERE id = :id
					 LIMIT 1
				'
			)
			->set_int(':id', $id)
			->execute()
			->fetch_array();
	}
	
	/**
	 * @return array public entry data
	 */
	static function entries($page, $limit, $offset = 0) 
	{
		return \app\SQL::prepare
			(
				__METHOD__,
				'
					SELECT *
					  FROM `'.static::table().'`
					 LIMIT :limit OFFSET :offset
				'
			)
			->page($page, $limit, $offset)
			->execute()
			->fetch_all();
	}
	
	/**
	 * Total entry count in table.
	 * 
	 * @return int count
	 */
	static function count()
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
	
	/**
	 * Checks if a value exists in the table, given a key. By default the title
	 * key is assumed.
	 * 
	 * @param mixed value
	 * @param string key
	 * @return bool
	 */
	static function exists($value, $key = 'title')
	{
		$count = \app\SQL::prepare
			(
				__METHOD__,
				'
					SELECT COUNT(1)
					  FROM `'.static::table().'` tbl
					 WHERE tbl.'.$key.' = :value
					 LIMIT 1
				',
				'mysql'
			)
			->set(':value', $value)
			->execute()
			->fetch_array()
			['COUNT(1)'];
		
		return ((int) $count) != 0;
	}
	
	/**
	 * Tests if a value is unique; opposite of exists.
	 * 
	 * @param mixed value
	 * @param string key
	 * @return bool
	 */
	static function not_exists($value, $key = 'title')
	{
		return ! static::exists($value, $key);
	}
	
	/**
	 * not_exists but applies to specified entry; used in updates, etc
	 * 
	 * @param mixed value
	 * @param int entry id
	 * @param string key
	 * @return bool
	 */
	static function unique_for($value, $id, $key = 'title')
	{
		$count = \app\SQL::prepare
			(
				__METHOD__,
				'
					SELECT COUNT(1)
					  FROM `'.static::table().'` row
					 WHERE row.'.$key.' = :value
					   AND NOT row.id = :id
					 LIMIT 1
				',
				'mysql'
			)
			->set_int(':id', $id)
			->set(':value', $value)
			->execute()
			->fetch_array()
			['COUNT(1)'];
		
		return ((int) $count) == 0;
	}
	
} # class

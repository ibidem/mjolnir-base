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
			('The [validator] method not implmented in ['.\called_class().'].');
	}
	
	/**
	 * Assemble fields after validation.
	 * 
	 * @param array fields
	 */
	public static function assemble(array $fields)
	{
		throw new \app\Exception_NotApplicable
			('The [assemble] method not implmented in ['.\called_class().'].');
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
		
		if ($validator === null || $validator->check())
		{
			static::assemble($fields);
		}
		else # did not pass validation
		{
			return $validator;
		}
		
		return null;
	}
	
} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Email extends \app\Instantiatable
{
	/**
	 * @return bool valid?
	 */
	static function valid($email)
	{
		// check length
		if (\strlen($email) > 254)
		{
			return false;
		}
		
		// check format
		if ( ! \preg_match('#^[^@]+@[^@]+\.[a-z]+#', $email))
		{
			return false;
		}
		
		return true;
	}

} # class

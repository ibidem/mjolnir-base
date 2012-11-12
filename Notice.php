<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Notice extends \app\Instantiatable
	implements \mjolnir\types\Document, \mjolnir\types\Executable
{
	use \app\Trait_Document;
	
	/**
	 * @var array notices on the system
	 */
	protected static $notices = null;
	
	/**
	 * Creates a notice for the user. You can grab the notice(s) via 
	 * `Notice::all()` and `Notice::last()`.
	 * 
	 * Don't forget to save the notice when creating it via `execute`;
	 */
	static function instance($message)
	{
		$instance = parent::instance();
		
		$instance->body($message);
		
		return $instance;
	}
	
	/**
	 * Initialize the notices.
	 */
	protected static function init()
	{
		if (static::$notices === null)
		{
			static::$notices = \app\Session::get('mjolnir:notices', []);
			
			// deserialize
			$object_notices = [];
			foreach (static::$notices as $notice)
			{
				$object_notices = static::unserialize_notice($notice);
			}
			
			static::$notices = $object_notices;
		}
	}
	
	/**
	 * Save the notice.
	 */
	function execute()
	{
		static::init();
		static::$notices[] = static::serialize_notice($this);
		\app\Session::set('mjolnir:notices', static::$notices);
	}
	
	/**
	 * Returns the array of notices; once returned the notices no longer exist
	 * in the system.
	 * 
	 * @return array notices
	 */
	static function all()
	{
		static::init();
		
		$object_notices = static::$notices;
		
		static::$notices = [];
		\app\Session::set('mjolnir:notices', static::$notices);
		
		return $object_notices;
	}
	
	/**
	 * @return string notice
	 */	
	protected static function serialize_notice($object_notice)
	{
		$string_notice = $object_notice->get_body();
		
		return $string_notice;
	}
	
	/**
	 * @return \app\Notice
	 */
	protected static function unserialize_notice($notice)
	{
		$object_notice = static::instance($notice);
		
		return $object_notice;
	}

} # class

<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Notice extends \app\Instantiatable
	implements \mjolnir\types\Document
{
	use \app\Trait_Document;
	
	/**
	 * @var array notices on the system
	 */
	protected static $notices = null;
	
	/**
	 * @var array
	 */
	protected $classes = [];
	
	/**
	 * Creates a notice for the user. You can grab the notice(s) via 
	 * `Notice::all()` and `Notice::last()`.
	 * 
	 * Don't forget to save the notice when creating it via `execute`;
	 */
	static function make($message)
	{
		$instance = static::instance();
		
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
		}
	}
	
	/**
	 * Notices classes.
	 * 
	 * @return \app\Notice $this
	 */
	function classes(array $classes)
	{
		$this->classes = $classes;
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	function get_classes()
	{
		return $this->classes;
	}
	
	/**
	 * Save the notice.
	 */
	function save()
	{
		static::init();
		static::$notices[] = $this;
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
		
		$notices = static::$notices;
		
		static::$notices = [];
		\app\Session::set('mjolnir:notices', static::$notices);
		
		return $notices;
	}

} # class

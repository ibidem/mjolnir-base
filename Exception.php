<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception extends \Exception
	implements \ibidem\types\Exception
{
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var boolean 
	 */
	protected static $show_fileinfo = false;
	
	/**
	 * Appends line and file to end of message for debug purposes. Should only
	 * be enabled in development; in production this can lead to a path reveal
	 * and thus various exploits.
	 */
	public static function enable_fileinfo()
	{
		static::$show_fileinfo = true;
	}
	
	/**
	 * @param string message
	 * @param string title
	 */
	public function __construct($message = null, $title = null)
	{
		$this->set_message($message);
		$this->set_title($title);
	}
	
	/**
	 * @return string message
	 */
	public function message()
	{
		return $this->message;
	}
	
	/**
	 * @return string title
	 */
	public function title()
	{
		return empty($this->title) ? 'Exception' : $this->title;
	}
	
	/**
	 * @param string exception type
	 * @return \ibidem\base\Exception $this
	 */
	public function type($type)
	{
		$this->type = $type;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function get_type()
	{
		return $this->type;
	}
	
	/**
	 * @param string message
	 * @return \ibidem\base\Exception $this
	 */
	public function set_message($message)
	{
		$this->message = $message;
		if (static::$show_fileinfo)
		{
			$file = \str_replace(DOCROOT, '', $this->getFile());
			$this->message .= ' ('.$file.' @ '.$this->getLine().')';
		}
		
		return $this;
	}
	
	/**
	 * @param string title
	 * @return \ibidem\base\Exception $this
	 */
	public function set_title($title = null)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @param \Exception PHP exception 
	 * @return \ibidem\base\Exception $this
	 */
	public function based_on(\Exception $source)
	{
		$this->message = $source->getMessage();
		return $this;
	}
	
} # class

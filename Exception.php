<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Exception extends \Exception
	implements 
		\kohana4\types\Instantiatable,
		\kohana4\types\Exception
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
	 * @deprecated use instance method instead 
	 */
	public function __construct($message = null, $title = null)
	{
		$this->message = $message;
		$this->title = $title;
	}
	
	/**
	 * @return \kohana4\types\Exception
	 */
	public static function instance($message = null, $title = null)
	{
		return new static($message, $title);
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
	 * @return $this
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
	 * @return $this 
	 */
	public function set_message($message)
	{
		$this->message = $message;
		return $this;
	}
	
	/**
	 * @param string title
	 * @return $this 
	 */
	public function set_title($title = null)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @param \Exception PHP exception 
	 * @return $this
	 */
	public function based_on(\Exception $source)
	{
		$this->message = $source->getMessage();
		return $this;
	}
	
} # class

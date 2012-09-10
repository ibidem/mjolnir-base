<?php namespace mjolnir\base;

/** 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception extends \Exception
	implements \mjolnir\types\Exception
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
	 * @param string message
	 * @param string title
	 */
	function __construct($message = null, $title = null)
	{
		$this->set_message($message);
		$this->set_title($title);
	}
	
	/**
	 * @return string message
	 */
	function message()
	{
		return $this->message;
	}
	
	/**
	 * @return string title
	 */
	function title()
	{
		return empty($this->title) ? 'Exception' : $this->title;
	}
	
	/**
	 * @param string exception type
	 * @return \mjolnir\base\Exception $this
	 */
	function type($type)
	{
		$this->type = $type;
		return $this;
	}
	
	/**
	 * @return string
	 */
	function get_type()
	{
		return $this->type;
	}
	
	/**
	 * @param string message
	 * @return \mjolnir\base\Exception $this
	 */
	function set_message($message)
	{
		$this->message = $message;
		
		return $this;
	}
	
	/**
	 * @param string title
	 * @return \mjolnir\base\Exception $this
	 */
	function set_title($title = null)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @param \Exception PHP exception 
	 * @return \mjolnir\base\Exception $this
	 */
	function based_on(\Exception $source)
	{
		$this->message = $source->getMessage();
		return $this;
	}
	
	/**
	 * @return string debug info or empty string if not in development
	 */
	static function debuginfo_for(\Exception $exception)
	{
		$out = '';
		if (\app\CFS::config('mjolnir/base')['development'])
		{
			$is_http = \app\Layer::find('http') !== null;
			$out .= $is_http ? '<div class="ibidem-debuginfo"><pre>' : '';
			$out .= $exception->getMessage()."\n";
			$out .= \str_replace(DOCROOT, '', $exception->getTraceAsString());
			$out .= $is_http ? '</pre></div>' : '';
		}
		
		return $out;
	}
	
} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Writer_Void extends \app\Instantiatable 
	implements \ibidem\types\Writer
{
	/**
	 * @var boolean
	 */
	protected $highlight_support = true;
	
	/**
	 * @var int
	 */
	protected $width;
	
	/**
	 * @var array
	 */
	protected $highlight;
	
	/**
	 * @var resource output stream
	 */
	protected $stdout;
	
	/**
	 * @var resource error stream
	 */
	protected $stderr;
	
	/**
	 * @var string
	 */
	protected $shell;
	
	/**
	 * @return \ibidem\base\Writer_Console
	 */
	public static function instance()
	{
		$instance = parent::instance();
		
		return $instance;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function width($width)
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function eol()
	{
		return $this;
	}
	
	/**
	 * @return string EOL as string
	 */
	function eol_string()
	{
		return PHP_EOL;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function write($text, $indent = 0, $nowrap_hint = null)
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function listwrite($dt, $dd, $indent_hint = null, $nowrap_hint = null)
	{		
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function highlight($text, $highlight = null)
	{		
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function writef($args)
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function header($title)
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function subheader($title)
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function status($status, $message, $highlight_hint = null)
	{
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function error($text)
	{
		return $this;
	}
	
} # class

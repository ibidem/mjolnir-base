<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
trait Trait_Meta
{
	/**
	 * @var array 
	 */
	protected $meta;
	
	/**
	 * Set metainformation for the document.
	 * 
	 * @param string key
	 * @param mixed value
	 * @return $this
	 */
	public function meta($key, $value)
	{
		$this->meta[$key] = $value;
		
		return $this;
	}
	
	/**
	 * @param string key
	 * @param mixed default
	 * @return mixed meta value for key, or default
	 */
	public function get_meta($key, $default = null)
	{
		return isset($this->meta[$key]) ? $this->meta[$key] : $default;
	}	
	
} # trait

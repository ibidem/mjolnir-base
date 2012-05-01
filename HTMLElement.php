<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class HTMLElement extends \app\Instantiatable
	implements \ibidem\types\Renderable
{	
	/**
	 * @var string
	 */
	protected $name = 'hr';
	
	/**
	 * @var array 
	 */
	private $attributes = array();
	
	/**
	 * @param array classes
	 * @return \ibidem\base\HTMLElement 
	 */
	private $classes;
	
	/**
	 * @return \ibidem\base\HTMLElement
	 */
	public static function instance($name = 'hr')
	{
		$instance = parent::instance();
		$instance->name = $name;
		
		return $instance;
	}
	
	/**
	 * @param string classes
	 * @return \ibidem\base\HTMLElement 
	 */
	public function classes(array $classes)
	{
		isset($this->classes) or $this->classes = array();
		
		foreach ($classes as $class)
		{
			if ( !\in_array($class, $this->classes))
			{
				$this->classes[] = $class;
			}
		}
		
		return $this;
	}
	
	/**
	 * @param string attribute 
	 * @return \ibidem\base\HTMLBlockElement $this
	 */
	public function remove_attribute($attribute)
	{
		unset($this->attributes[$attribute]);
		
		return $this;
	}
	
	/**
	 * @return array 
	 */
	public function get_classes()
	{
		return $this->classes;
	}
	
	/**
	 * @param string name
	 * @param string value 
	 * @return \ibidem\base\HTMLElement
	 */
	public function attribute($name, $value = null)
	{
		if ($value !== null)
		{
			$this->attributes[$name] = $value;
		}
		else # no value; assume single tag
		{
			$this->attributes[$name] = '';
		}
		
		return $this;
	}
	
	/**
	 * @param string name
	 * @return string
	 */
	public function get_attribute($name)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}
	
	/**
	 * @param string id 
	 * @return \ibidem\base\HTMLElement
	 */
	public function id($id)
	{
		return $this->attribute('id', $id);
	}
	
	/**
	 * @return string
	 */
	public function render_attributes()
	{
		$attributes = '';
		foreach ($this->attributes as $name => $value)
		{
			$attributes .= ' '.$name.'="'.$value.'"';
		}
		if ($this->classes)
		{
			$classes = \array_shift($this->classes);
			foreach ($this->classes as $class)
			{
				$classes .= ' '.$class;
			}
			$attributes .= ' class="'.$classes.'"';
		}
		
		return $attributes;
	}
	
	/**
	 * @return string
	 */
	public function render()
	{
		return '<'.$this->name.$this->render_attributes().'/>';
	}
	
	/**
	 * @return string 
	 */
	public function __toString()
	{
		try
		{
			return $this->open();
		}
		catch (\Exception $e)
		{
			return '[ERROR: '.$e->getMessage().']';
		}
	}
	
} # class

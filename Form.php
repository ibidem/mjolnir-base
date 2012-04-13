<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Form extends \app\Instantiatable
{
	/**
	 * @var integer 
	 */
	private static $forms_counter = 0;
	
	/**
	 * @var string 
	 */
	private $action;
	
	/**
	 * @var boolean
	 */
	private $secure;
	
	/**
	 * @return string
	 */
	private $method;
	
	/**
	 * @var string
	 */
	private $id;
	
	/**
	 * @var string 
	 */
	private $field_template;
	
	/**
	 * @var string
	 */
	private $group_start;
	
	/**
	 * @var string 
	 */
	private $group_end;
	
	/**
	 * @var string
	 */
	private $classes;
	
	/**
	 * @return \ibidem\base\Form 
	 */
	public static function instance()
	{
		$config = \app\CFS::config('ibidem/form');
		$instance = parent::instance();
		$instance->secure = $config['secure.default'];
		$instance->method = $config['method.default'];
		$instance->field_template = $config['template.field'];
		
		list($instance->group_start, $instance->group_end) 
			= \explode(':fields', $config['template.group']);
		
		$instance->id = 'form_'.self::$forms_counter++;
		
		return $instance;
	}
	
	/**
	 * @param string action
	 * @return \ibidem\base\Form $this
	 */
	public function action($action)
	{
		$this->action = $action;
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Form $this
	 */
	public function insecure()
	{
		$this->secure = false;
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Form $this
	 */
	public function secure()
	{
		$this->secure = true;
		return $this;
	}
	
	/**
	 * @param string legend
	 * @return string
	 */
	public function group($legend)
	{
		return \strtr($this->group_start, ':legend', $legend);
	}
	
	/**
	 * End of group.
	 * 
	 * @return string
	 */
	public function end()
	{
		return $this->group_end;
	}
	
	/**
	 * @return string 
	 */
	public function open()
	{
		if ($this->classes)
		{
			return "<form class=\"{$this->classes}\" action=\"{$this->action}\" method=\"{$this->method}\">";
		}
		else # no classes
		{
			return "<form action=\"{$this->action}\" method=\"{$this->method}\">";
		}
	}
	
	/**
	 * @return string 
	 */
	public function close()
	{
		return "</form>";
	}
	
	/**
	 * @param string classes
	 * @return \ibidem\base\Form 
	 */
	public function classes($classes)
	{
		$this->classes = $classes;
		return $this;
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function text($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this->id)
			->template($this->field_template);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Submit
	 */
	public function submit($title, $name = null)
	{
		return \app\FormField_Submit::instance($title, $name, $this->id)
			->template($this->field_template)
			->value($title);
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

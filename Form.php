<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Form extends \app\HTMLBlockElement
{
	/**
	 * @var integer 
	 */
	private static $forms_counter = 0;
	
	/**
	 * @var integer 
	 */
	private static $tabindex = 1;
	
	/**
	 * @var boolean
	 */
	private $secure;
	
	/**
	 * @var string
	 */
	private $form_id;
	
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
	 * @return \ibidem\base\Form $this
	 */
	public static function instance()
	{
		$config = \app\CFS::config('ibidem/form');
		$instance = parent::instance();
		$instance->secure = $config['secure.default'];
		$instance->field_template = $config['template.field'];
		$instance->attribute('method', $config['method.default']);
		
		list($instance->group_start, $instance->group_end) 
			= \explode(':fields', $config['template.group']);
		
		$instance->form_id = 'form_'.self::$forms_counter++;
		
		return $instance;
	}
	
	/**
	 * @param string template
	 * @return \ibidem\base\Form $this
	 */
	public function field_template($template)
	{
		$this->field_template = $template;
		return $this;
	}
	
	/**
	 * @param string $method
	 * @return \ibidem\base\Form $this
	 */
	public function method($method)
	{
		$this->attribute('method', $method);
		return $this;
	}
	
	/**
	 * @param string action
	 * @return \ibidem\base\Form $this
	 */
	public function action($action)
	{
		$this->attribute('action', $action);
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
		return \strtr($this->group_start, array(':legend' => $legend));
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
		return "<form{$this->render_attributes()}>";	
	}
	
	/**
	 * @return string 
	 */
	public function close()
	{
		return "</form>";
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function text($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this->form_id)
			->template($this->field_template);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function telephone($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this->form_id)
			->template($this->field_template);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function email($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this->form_id)
			->template($this->field_template);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return string 
	 */
	public function textarea($title, $name)
	{
		return \app\FormField_TextArea::instance($title, $name, $this->form_id)
			->template($this->field_template);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @param array values
	 * @param string default
	 * @return string
	 */
	public function radio($title, $name, array $values, $default)
	{
		return \app\FormField_Radio::instance($title, $name, $this->form_id)
			->template($this->field_template)
			->default_value($default)
			->values($values);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Submit
	 */
	public function submit($title, $name = null)
	{
		return \app\FormField_Submit::instance($title, $name, $this->form_id)
			->template($this->field_template)
			->value($title);
	}
	
	/**
	 * @return integer 
	 */
	public static function tabindex()
	{
		return self::$tabindex++;
	}

} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Form extends \app\HTMLBlockElement
	implements \ibidem\types\Form
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
	private $form_id = 'global';
	
	/**
	 * @var string 
	 */
	private $field_template;
	
	/**
	 * @var array
	 */
	private $targetted_field_template;
	
	/**
	 * @var string
	 */
	private $group_start;
	
	/**
	 * @var string 
	 */
	private $group_end;

	/**
	 * @var boolean
	 */
	private $auto_complete;
	
	/**
	 * @var array 
	 */
	private $registerd_hidden = array();
	
	/**
	 * @var null|array
	 */
	private $errors;

	/**
	 * @return \ibidem\base\Form $this
	 */
	public static function instance($id = null)
	{
		$config = \app\CFS::config('ibidem/form');
		$instance = parent::instance();
		$instance->secure = $config['secure.default'];
		$instance->field_template = $config['template.field'];
		$instance->targetted_field_template = array();
		$instance->attribute('method', $config['method.default']);
		
		list($instance->group_start, $instance->group_end) 
			= \explode(':fields', $config['template.group']);
		
		if ($id !== null)
		{
			$instance->form_id = $id;
		}
		else # null id, annonymous form
		{
			$instance->form_id = 'form_'.self::$forms_counter++;
		}
		
		$instance->attribute('id', $instance->form_id);
		
		// register hidden field for when form is opened and method not GET
		if ($config['method.default'] !== \ibidem\types\HTTP::GET)
		{
			$instance->registerd_hidden['form'] = $instance->form_id;
		}
		
		// check if this form was previously submitted
		if (isset($_POST['form']) && $_POST['form'] == $instance->form_id)
		{
			$instance->auto_complete = & $_POST;
		}
		else # not post, or not this form
		{
			$instance->auto_complete = null;
		}
		
		return $instance;
	}
	
	/**
	 * Optimizes form for file upload.
	 * 
	 * @return \ibidem\base\Form $this
	 */
	public function file_uploader()
	{
		$this->attribute('enctype', 'multipart/form-data');
		return $this;
	}
	
	/**
	 * @param array fields
	 * @return \ibidem\base\Form $this
	 */
	public function auto_complte(array $fields = null)
	{
		if ($fields === null)
		{
			return $this;
		}
		
		if ($this->auto_complete === null)
		{
			$this->auto_complete = array();
		}
		
		foreach ($fields as $key => $field)
		{
			$this->auto_complete[$key] = $field;
		}
		
		return $this;
	}
	
	/**
	 * @param string name
	 * @return mixed|null 
	 */
	public function field_value($name)
	{
		if ($this->auto_complete !== null && isset($this->auto_complete[$name]))
		{
			return $this->auto_complete[$name];
		}
		else # no auto_complte for field
		{
			return null;
		}
	}
	
	/**
	 * @return string 
	 */
	public function form_id()
	{
		return $this->form_id;
	}
	
	/**
	 * @param string key 
	 * @return array|null errors
	 */
	public function errors_for($key)
	{
		if ($this->errors !== null)
		{
			if (isset($this->errors[$key]))
			{
				return $this->errors[$key];
			}
			else # not set
			{
				return null;
			}
		}
		
		// no errors
		return null;
	}
	
	/**
	 * @param string id
	 * @return string unique id namespaced to form
	 */
	public function for_label($id)
	{
		// add form namespace
		return $this->form_id.'-'.$id;
	}
	
	/**
	 * @param string template
	 * @return \ibidem\base\Form $this
	 */
	public function field_template($template, array $targets = null)
	{
		if ($targets === null)
		{
			$this->field_template = $template;
		}
		else # targetted template
		{
			if ($template === null)
			{
				foreach ($targets as $target)
				{
					unset($this->targetted_field_template[$target]);
				}
			}
			else # template not null
			{	
				foreach ($targets as $target)
				{
					$this->targetted_field_template[$target] = $template;
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * @param array field errors
	 * @return \ibidem\base\Form $this
	 */
	public function errors(array & $errors = null)
	{
		$this->errors = & $errors;
		return $this;
	}
	
	/**
	 * @param string $method
	 * @return \ibidem\base\Form $this
	 */
	public function method($method)
	{
		$this->attribute('method', $method);
		
		if ($method === \ibidem\types\HTTP::GET)
		{
			unset($this->registerd_hidden['form']);
		}
		
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
		$output = "<form{$this->render_attributes()}>";	
		if ( ! empty($this->registerd_hidden))
		{
			foreach ($this->registerd_hidden as $key => $value)
			{
				$output .= $this->hidden($key)->value($value)->render();
			}
		}
		
		return $output;
	}
	
	/**
	 * @return string 
	 */
	public function close()
	{
		return "</form>";
	}
	
	public function get_field_template($name)
	{
		if (isset($this->targetted_field_template[$name]))
		{
			return $this->targetted_field_template[$name];
		}
		else # not targetted
		{
			return $this->field_template;
		}
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function text($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this)
			->template($this->get_field_template('text'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Password
	 */
	public function password($title, $name)
	{
		return \app\FormField_Password::instance($title, $name, $this)
			->template($this->get_field_template('password'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function telephone($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this)
			->template($this->get_field_template('telephone'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function email($title, $name)
	{
		return \app\FormField_Text::instance($title, $name, $this)
			->template($this->get_field_template('email'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Text
	 */
	public function file($title, $name)
	{
		return \app\FormField_File::instance($title, $name, $this)
			->template($this->get_field_template('file'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_DateTime
	 */
	public function datetime($title, $name)
	{
		return \app\FormField_DateTime::instance($title, $name, $this)
			->template($this->get_field_template('datetime'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \app\FormField_TextArea 
	 */
	public function textarea($title, $name)
	{
		return \app\FormField_TextArea::instance($title, $name, $this)
			->template($this->get_field_template('textarea'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @param array values
	 * @param string default
	 * @return \app\FormField_Radio
	 */
	public function radio($title, $name, array $values, $default)
	{
		return \app\FormField_Radio::instance($title, $name, $this)
			->template($this->get_field_template('radio'))
			->value($default)
			->values($values);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Checkbox
	 */
	public function checkbox($title, $name)
	{
		return \app\FormField_Checkbox::instance($title, $name, $this)
			->template($this->get_field_template('checkbox'));
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @param array values
	 * @return \ibidem\base\FormField_Select
	 */
	public function select($title, $name, array $values = null)
	{
		return \app\FormField_Select::instance($title, $name, $this)
			->template($this->get_field_template('select'))
			->values($values);
	}
	
	/**
	 * @param string title
	 * @param string name
	 * @return \ibidem\base\FormField_Submit
	 */
	public function submit($title, $name = null)
	{
		return \app\FormField_Submit::instance($title, $name, $this)
			->template($this->get_field_template('submit'))
			->value($title);
	}
	
	/**
	 * @param string name
	 * @return \ibidem\base\FormField_Hidden
	 */
	public function hidden($name)
	{
		return \app\FormField_Hidden::instance($name, $this);
	}
	
	/**
	 * @return integer 
	 */
	public static function tabindex()
	{
		return self::$tabindex++;
	}

} # class

<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField extends \app\HTMLElement
{
	/**
	 * @var string 
	 */
	protected static $tag_name = 'input';
	
	/**
	 * @var string 
	 */
	protected $type = 'text';
	
	/**
	 * @var string
	 */
	protected $name = 'input';
	
	/**
	 * @var type 
	 */
	protected $tabindex;
	
	/**
	 * @var string
	 */
	protected $form;	
	
	/**
	 * @var string
	 */
	private $template;
	
	/**
	 * @param string title
	 * @param string name
	 * @param \ibidem\types\Form form
	 * @return \ibidem\base\FormField
	 */
	public static function instance($title = null, $name = null, \ibidem\types\Form $form = null)
	{
		$instance = parent::instance(static::$tag_name);
		$instance->title = $title;
		$instance->attribute('name', $name);
		if ($instance->type)
		{
			$instance->attribute('type', $instance->type);
		}
		$instance->tabindex = \app\Form::tabindex();
		$instance->attribute('tabindex', $instance->tabindex);
		$instance->form = $form;
			
		if ($instance->type !== 'hidden' && $instance->type !== 'password' && ($field_value = $form->field_value($name)) !== null)
		{
			$instance->value($field_value);
		}
		
		return $instance;
	}
	
	/**
	 * @param string value 
	 * @return \ibidem\base\FormField $this 
	 */
	public function value($value)
	{
		$this->attribute('value', $value);
		return $this;
	}
	
	/**
	 * @return \ibidem\base\FormField $this
	 */
	public function disabled()
	{
		$this->attribute('disabled');
		return $this;
	}
	
	/**
	 * @param string template
	 * @return \ibidem\base\FormField
	 */
	public function template($template)
	{
		$this->template = $template;
		return $this;
	}
	
	/**
	 * @return string 
	 */
	public function get_template()
	{
		return $this->template;
	}
	
	/**
	 * @return \ibidem\base\FormField $this
	 */
	public function field()
	{
		$this->template = ':field';
		return $this;
	}
	
	/**
	 * @return \ibidem\base\FormField $this
	 */
	public function unnamed()
	{
		$this->remove_attribute('name');
		
		return $this;
	}
	
	/**
	 * @return string 
	 */
	protected function render_name()
	{
		$classes = $this->get_classes();
		if ($classes)
		{
			return \app\HTMLBlockElement::instance('label', $this->title)
				->attribute('for', $this->form->form_id().'_'.$this->tabindex)
				->classes($this->get_classes())
				->render();
		}
		else # has no classes
		{
			return \app\HTMLBlockElement::instance('label', $this->title)
				->attribute('for', $this->form->form_id().'_'.$this->tabindex)
				->render();
		}
	}
	
	/**
	 * @return string 
	 */
	protected function render_field()
	{
		$field = '<'.$this->name.' id="'.$this->form->form_id().'_'.$this->tabindex.'"'.$this->render_attributes().'/>';
		if ($errors = $this->form->errors_for($this->get_attribute('name')))
		{
			$field .= '<ul class="errors">';
			foreach ($errors as $error)
			{
				$field .= '<li>'.\app\Lang::tr($error).'</li>';
			}
			$field .= '</ul>';
		}
		
		return $field;
	}
	
	/**
	 * @return string 
	 */
	public function render()
	{
		return \strtr
			(
				$this->template, 
				array
				(
					':name' => $this->render_name(),
					':field' => $this->render_field()
				)
			);
	}
	
	/**
	 * [!!] this is a shorhand for render; if possible just use render directly
	 * 
	 * @return string 
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (\Exception $e)
		{
			return '[ERROR: '.$e->getMessage().']';
		}
	}

} # class

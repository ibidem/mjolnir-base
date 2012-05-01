<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField_Radio extends \app\FormField
{	
	/**
	 * @var string 
	 */
	protected $type = 'radio';
	
	/**
	 * @var string 
	 */
	protected $default_value;
	
	/**
	 * @var array 
	 */
	protected $values;
	
	/**
	 * @param string default
	 * @return \ibidem\base\FormField_Radio $this
	 */
	public function value($default_value)
	{
		// default value can only be set once
		if ($this->default_value === null)
		{
			$this->default_value = $default_value;
		}
		
		return $this;
	}
	
	/**
	 * @param array values
	 * @return \ibidem\base\FormField_Radio $this
	 */
	public function values(array $values)
	{
		$this->values = $values;	
		return $this;
	}
	
	/**
	 * @return string 
	 */
	protected function render_field()
	{
		$field = '';
		
		$field_id = $this->form->form_id().'_'.$this->tabindex;
		
		foreach ($this->values as $value => $title)
		{
			$field .= ' <label for="'.$field_id.'_'.$value.'"><span class="label-title">'.$title.'</span> <'.$this->name.' id="'.$field_id.'_'.$value.'"'.$this->render_attributes().($this->default_value === $value ? ' checked="checked"' : '').' value="'.$value.'"/></label>';
		}
		
		return $field;
	}

} # class

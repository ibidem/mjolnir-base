<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField_Select extends \app\FormField
{
	/**
	 * @var string 
	 */
	protected static $tag_name = 'select';
	
	/**
	 * @var string 
	 */
	protected $type = null;
	
	/**
	 * @var array 
	 */
	protected $values = array();
	
	/**
	 * @var string 
	 */
	protected $active;

	/**
	 * @param array values 
	 * @return \ibidem\base\FormField_Select $this
	 */
	public function values(array $values)
	{
		foreach ($values as $key => $value)
		{
			$this->values[$key] = $value;
		}
		
		return $this;
	}
	
	/**
	 * @param string id
	 * @return \ibidem\base\FormField_Select $this
	 */
	public function value($id)
	{
		$this->active = $id;
		return $this;
	}
	
	/**
	 * @return string 
	 */
	protected function render_field()
	{
		$field = '<'.$this->name.' id="'.$this->form->form_id().'_'.$this->tabindex.'"'.$this->render_attributes().'>';
		foreach ($this->values as $title => $key)
		{
			if ($key == $this->active)
			{
				$field .= '<option value="'.$key.'" selected="selected">'.$title.'</option>';
			}
			else
			{
				$field .= '<option value="'.$key.'">'.$title.'</option>';
			}
		}
		$field .= '</'.$this->name.'>';
		return $field;
	}
	
} # class

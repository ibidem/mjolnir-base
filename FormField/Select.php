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
	 * @return string 
	 */
	protected function render_field()
	{
		$field = '<'.$this->name.' id="'.$this->form.'_'.$this->tabindex.'"'.$this->render_attributes().'>';
		foreach ($this->values as $title => $key)
		{
			$field .= '<option value="'.$key.'">'.$title.'</option>';
		}
		$field .= '</'.$this->name.'>';
		return $field;
	}
	
} # class

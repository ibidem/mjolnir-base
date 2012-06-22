<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField_Checkbox extends \app\FormField
{
	/**
	 * @var string 
	 */
	protected $type = 'checkbox';
	
	/**
	 * @param bool checked
	 * @return \ibidem\base\FormField_Checkbox $this
	 */
	function checked($checked = true)
	{
		if ($checked)
		{
			$this->attribute('checked', 'checked');
		}
		else # not checked
		{
			$this->remove_attribute('checked');
		}
		
		return $this;
	}

} # class

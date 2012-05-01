<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField_Hidden extends \app\FormField
{
	/**
	 * @var string 
	 */
	protected $type = 'hidden';
	
	/**
	 * @param string name
	 * @param \ibidem\types\Form form 
	 * @return \ibidem\base\FormField_Hidden
	 */
	public static function instance($name = null, \ibidem\types\Form $form = null)
	{
		return parent::instance(null, $name, $form);
	}
	
	/**
	 * @return string 
	 */
	public function render()
	{
		return $this->render_field();
	}
	
} # class

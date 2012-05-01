<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField_TextArea extends \app\FormField
{
	/**
	 * @var string 
	 */
	protected static $tag_name = 'textarea';
	
	/**
	 * @var string 
	 */
	protected $type = null;
	
	/**
	 * @var string
	 */
	private $body = '';
	
	/**
	 * @param string title
	 * @param string name
	 * @param \ibidem\types\Form form
	 * @return \ibidem\base\FormField_TextArea
	 */
	public static function instance($title = null, $name = null, \ibidem\types\Form $form = null)
	{
		$form_config = \app\CFS::config('ibidem/form');
		$instance = parent::instance($title, $name, $form);
		$instance->attribute('rows', $form_config['textarea.rows.default']);
		$instance->attribute('cols', $form_config['textarea.cols.default']);
		
		return $instance;
	}
	
	/**
	 * @param string body
	 * @return \ibidem\base\HTMLBlockElement 
	 */
	public function body($body = '')
	{
		$this->body = $body;
		return $this;
	}
	
	/**
	 * @return string 
	 */
	protected function render_field()
	{
		return '<'.$this->name.' id="'.$this->form->form_id().'_'.$this->tabindex.'"'.$this->render_attributes().'>'
			. $this->body
			. '</'.$this->name.'>';
	}

} # class

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
	protected $type = 'text';
	
	/**
	 * @var string
	 */
	protected $name = 'input';
	
	/**
	 * @var type 
	 */
	private $tabindex;
	
	/**
	 * @var string
	 */
	private $template;
	
	/**
	 * @var string
	 */
	private $form;
	
	/**
	 * @param string title
	 * @param string name
	 * @param string form
	 * @return \ibidem\base\FormField
	 */
	public static function instance($title = null, $name = null, $form = 'global')
	{
		$instance = parent::instance('input');
		$instance->title = $title;
		$instance->attribute('name', $name);
		$instance->attribute('type', $instance->type);
		$instance->tabindex = \app\Form::tabindex();
		$instance->attribute('tabindex', $instance->tabindex);
		$instance->form = $form;
		
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
	public function render_name()
	{
		
		return \app\HTMLBlockElement::instance('label', $this->title)
			->attribute('for', $this->form.'_'.$this->tabindex)->render();
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
					':field' => parent::render()
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

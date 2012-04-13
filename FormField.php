<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField extends \app\Instantiatable
{
	/**
	 * @var string 
	 */
	protected $type = 'text';
	
	/**
	 * @var string
	 */
	private $css_class;
	
	/**
	 * @var string
	 */
	private $template;
	
	/**
	 * @var string
	 */
	private $form;
	
	/**
	 * @var string 
	 */
	private $value = '';
	
	/**
	 * @param string title
	 * @param string name
	 * @param string form
	 * @return \ibidem\base\FormField
	 */
	public static function instance($title = null, $name = null, $form = 'global')
	{
		$instance = parent::instance();
		$instance->title = $title;
		$instance->name = $name;
		$instance->form = $form;
		return $instance;
	}
	
	/**
	 * @param string classes
	 * @return \ibidem\base\FormField $this 
	 */
	public function css_class($classes)
	{
		$this->css_class;
		return $this;
	}
	
	/**
	 * @param string value 
	 * @return \ibidem\base\FormField $this 
	 */
	public function value($value)
	{
		$this->value = $value;
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
		return "<label for=\"{$this->form}_{$this->name}\">{$this->title}</label>";
	}
	
	/**
	 * @return string
	 */
	public function render_field()
	{
		if ($this->name)
		{
			return "<input id=\"{$this->form}_{$this->name}\" type=\"{$this->type}\" name=\"{$this->name}\" value=\"{$this->value}\" />";
		}
		else # no name (does not appear in queries)
		{
			return "<input id=\"{$this->form}_{$this->name}\" type=\"{$this->type}\" value=\"{$this->value}\" />";
		}
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

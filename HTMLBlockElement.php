<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class HTMLBlockElement extends \app\HTMLElement
{
	/**
	 * @var string
	 */
	private $body = '';
	
	/**
	 * @return \ibidem\base\HTMLElement
	 */
	public static function instance($name = 'div', $body = '')
	{
		$instance = parent::instance($name);
		$instance->body = $body;
		
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
	public function render()
	{
		return '<'.$this->name.$this->render_attributes().'>'.$this->body.'</'.$this->name.'>';
	}

} # class

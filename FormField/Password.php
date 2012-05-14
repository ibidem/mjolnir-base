<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class FormField_Password extends \app\FormField_Text
{
	/**
	 * @var string
	 */
	protected $type = 'password';

	/**
	 * @param boolean state
	 * @return \ibidem\base\FormField_Password $this
	 */
	public function autocomplete($state = true) 
	{
		$this->attribute('autocomplete', $state ? 'on' : 'off');
		return $this;
	}
	
} # class

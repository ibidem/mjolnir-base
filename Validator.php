<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Validator extends \app\Instantiatable
{
	/**
	 * @var array 
	 */
	private $fields;
	
	/**
	 * @var 
	 */
	public static function instance(array $fields)
	{
		$this->fields($fields);
	}
	
	/**
	 * @param array fields
	 * @return \ibidem\base\Validator $this
	 */
	public function fields(array $fields)
	{
		$this->fields = $fields;
		return $this;
	}
	
	/**
	 * @param array fields
	 * @return \ibidem\base\Validator $this
	 */
	public function rule(array $args)
	{
		$args = \func_get_args();
		
		$field = \array_shift($args);
		$callback = \array_shift($args);
		\array_unshift($args, $this->fields[$field]);
		
		return $this;
	}

} # class

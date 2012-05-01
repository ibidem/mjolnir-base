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
	 * @var array
	 */
	private $errors;
	
	/**
	 * @var array 
	 */
	private $rules;
	
	/**
	 * @param string config with errors (should contain "errors" on route)
	 * @param array fields
	 * @return \ibidem\base\Validator 
	 */
	public static function instance(array $errors = null, array $fields = null)
	{
		$instance = parent::instance();
		$instance->rules = array();
		$errors and $instance->errors($errors);
		$fields and $instance->fields($fields);
		
		return $instance;
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
	 * @param array errors
	 * @return \ibidem\base\Validator $this
	 */
	public function errors(array $errors)
	{
		
		$this->errors = $errors;
		return $this;
	}
	
	/**
	 * @param array $args
	 * @return \ibidem\base\Validator $this
	 */
	public function rule($args)
	{
		$args = \func_get_args();
		
		$this->rules[] = $args;

		return $this;
	}
	
	/**
	 * @return array|null array of errors on failure, null on success
	 */
	public function validate()
	{
		static $errors = null;

		// calculated?
		if ($errors === null)
		{
			$errors = array();
			foreach ($this->rules as $args)
			{
				$field = \array_shift($args);
				$callback = \array_shift($args);
				\array_unshift($args, $this->fields[$field]);

				if (\strpos($callback, '::') === false)
				{
					// default to generic rule set
					$callfunc = '\app\ValidatorRules::'.$callback;
				}
				else
				{
					$callfunc = $callback;
				}


				if ( ! \call_user_func_array($callfunc, $args))
				{
					// gurantee error field exists as an array
					isset($errors[$field]) or $errors[$field] = array();

					if ( ! isset($this->errors[$field]))
					{
						throw new \app\Exception_NotFound
							("Missing error messages for [$field].");
					}

					if ( ! isset($this->errors[$field][$callback]))
					{

						// generic rules won't work since everything will just look
						// wrong if we print the same message two or three times as
						// a consequence of the user getting several things wrong 
						// for the same field
						throw new \app\Exception_NotFound
							("Missing error message for when [$field] fails [$callback].");
					}

					// add errors based on error field
					$errors[$field][$callback] = $this->errors[$field][$callback];
				}
			}
		}
		
		// return null if no errors or array with error messages
		return empty($errors) ? null : $errors;
	}

} # class

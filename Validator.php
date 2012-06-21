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
	 * @var array 
	 */
	private $errors_cache = null;
	
	/**
	 * @param string config with errors (should contain "errors" on route)
	 * @param array fields
	 * @return \ibidem\base\Validator 
	 */
	public static function instance(array $errors = null, array $fields = null)
	{
		$instance = parent::instance();
		$instance->rules = array();
		
		if ($errors === null)
		{
			$instance->errors(array());
		}
		else # errors not null
		{
			$instance->errors($errors);
		}
		
		if ($errors === null)
		{
			$instance->fields(array());
		}
		else # errors not null
		{
			$instance->fields($fields);
		}
		
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
		// calculated?
		if ($this->errors_cache === null)
		{
			$this->errors_cache = array();
			foreach ($this->rules as $args)
			{
				$field = \array_shift($args);
				
				if ( ! isset($this->fields[$field]))
				{
					throw new \app\Exception_NotAllowed
						('Inconsistent fields passed to validation. Missing field: '.$field);
				}
				
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
					isset($this->errors_cache[$field]) or $this->errors_cache[$field] = array();
					
					if ( ! isset($this->errors[$field][$callback]))
					{
						// try to use general ruleset
						$general_errors = \app\CFS::config('ibidem/general-errors');
						if (isset($general_errors[$callback]))
						{
							// get the general message
							$this->errors_cache[$field][$callback] = $general_errors[$callback];
						}
						else # not a general rule
						{
							// generic rules won't work since everything will just look
							// wrong if we print the same message two or three times as
							// a consequence of the user getting several things wrong 
							// for the same field
							throw new \app\Exception_NotFound
								("Missing error message for when [$field] fails [$callback].");
						}
					}
					else # errors are set
					{
						$this->errors_cache[$field][$callback] = $this->errors[$field][$callback];
					}

					
					// add errors based on error field
					//$this->errors_cache[$field][$callback] = $errors[$field][$callback];
				}
			}
		}
		
		// return null if no errors or array with error messages
		return empty($this->errors_cache) ? null : $this->errors_cache;
	}
	
	/**
	 * This method is designed for unit testing.
	 * 
	 * @return array 
	 */
	public function all_errors()
	{
		// calculated?
		$errors = array();
		foreach ($this->rules as $args)
		{
			$field = \array_shift($args);

			if ( ! isset($this->fields[$field]))
			{
				throw new \app\Exception_NotAllowed
					('Inconsistent fields passed to validation. Missing field: '.$field);
			}

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

			// gurantee error field exists as an array
			isset($errors[$field]) or $errors[$field] = array();

			isset($this->errors[$field]) or $this->errors[$field] = array();

			if ( ! isset($this->errors[$field][$callback]))
			{
				// try to use general ruleset
				$general_errors = \app\CFS::config('ibidem/general-errors');
				if (isset($general_errors[$callback]))
				{
					// get the general message
					$errors[$field][$callback] = $general_errors[$callback];
				}
				else # not a general rule
				{
					// generic rules won't work since everything will just look
					// wrong if we print the same message two or three times as
					// a consequence of the user getting several things wrong 
					// for the same field
					throw new \app\Exception_NotFound
						("Missing error message for when [$field] fails [$callback].");
				}
			}
			else # callback is defined
			{
				// add errors based on error field
				$errors[$field][$callback] = $this->errors[$field][$callback];
			}

			// check if rule is callable
			$class_method = \explode('::', $callback);
			if (\count($class_method) == 1) 
			{
				\array_unshift($class_method, '\app\ValidatorRules');
			}
			
			if ( ! \method_exists($class_method[0], $class_method[1]))
			{
				throw new \app\Exception_NotApplicable
					('The method ['.$callback.'] is not defined.');
			}
		}
		
		return $errors;
	}

} # class

<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Auditor extends \app\Instantiatable implements \mjolnir\types\Validator
{
	use \app\Trait_Validator
		{
			fields_array as private trait_fields_array;
			check as private trait_check;
			errors as private trait_errors;
		}

	/**
	 * @var array of callable
	 */
	protected $rules;
	
	/**
	 * @var boolean
	 */
	protected $processedrules = false;
	
	/**
	 * @return static
	 */
	static function instance(array $fields = null)
	{
		$instance = parent::instance();
		$fields === null or $instance->fields_array($fields);
		
		return $instance;
	}

	/**
	 * A field will be tested against a claim and validated by the proof, or if
	 * the proof is null the claim will provide the proof itself. You may only
	 * specify proofs as callable or array of callable.
	 *
	 * Field and claim may be an array, and proof may be a function. The array
	 * version will always be translated down to the non-array version.
	 *
	 * Unlike a Validator you may run an Auditor on mutiple field sets and also
	 * on partial data since an Auditor won't perform a rule if the current 
	 * field set does not contain the field in question.
	 * 
	 * eg.
	 *
	 *	   // check a password is not empty
	 *     $auditor->rule('password', 'not_empty');
	 *
	 *     // check both a password and title are not empty
	 *	   $auditor->rule(['title', 'password'], 'not_empty');
	 *
	 *     // check multiple fields
	 *     $auditor->rule
	 *         (
	 *             ['given_name', 'family_name'],
	 *             function ($fields, $field))
	 *             {
	 *                 return ! static::exists($fields[$field], $field, $fields['id']);
	 *             }
	 *         );
	 *
	 * @return static $this
	 */
	protected function addrule($field, $claim, $proof = null)
	{
		if (isset($this->fields[$field]))
		{
			$this->processedrules = false;

			$auditor = $this;
			if ($proof === null)
			{
				$this->rules[] = function () use ($auditor, $field, $claim)
					{
						$rules = \app\CFS::config('mjolnir/validator')['rules'];
						if ( ! $rules[$claim]($auditor->fields, $field))
						{
							$auditor->adderror($field, $claim);
						}
					};
			}
			else # callback
			{
				$this->rules[] = function () use ($auditor, $field, $claim, $proof)
					{
						if ( ! $proof($auditor->fields, $field))
						{
							$auditor->adderror($field, $claim);
						}
					};
			}
		}
	}

	/**
	 * @return static $this
	 */
	function fields_array($fields)
	{
		$this->errors = null;
		$this->processedrules = false;
		return $this->trait_fields_array($fields);
	}

	/**
	 * @return static $this
	 */
	function check()
	{
		if ( ! $this->processedrules)
		{
			foreach ($this->rules as $rule_logic) 
			{
				$rule_logic();
			}
			
			$this->processedrules = true;
		}
		
		return $this->trait_check();
	}
	
	/**
	 * @return array
	 */
	function errors()
	{
		if ( ! $this->processedrules)
		{
			$this->check();
		}
		
		return $this->trait_errors();
	}
	
} # class

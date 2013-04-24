<?php namespace mjolnir\base;

/**
 * Auditor supports exporting supported rules and also can be run multiple 
 * times against different field sets.
 * 
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Auditor extends \app\Instantiatable implements \mjolnir\types\Exportable, \mjolnir\types\Validator 
{
	use \app\Trait_Exportable;
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
	 * @var array rule definitions
	 */
	protected $ruledef;
	
	/**
	 * @var boolean
	 */
	protected $processedrules = false;

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
				isset($this->ruledef[$field]) or $this->ruledef[$field] = [];
				\in_array($field, $this->ruledef) or $this->ruledef[$field][$claim] = $this->geterror($field, $claim);
				
				$this->rules[] = function () use ($auditor, $field, $claim)
					{
						$rules = \app\CFS::config('mjolnir/validator')['rules'];
						if ( ! $rules[$claim]($auditor->fields, $field))
						{
							$auditor->adderror($field, $claim);
						}
					};
			}
			else # proof !== null
			{
				$this->addrule_with_proof($field, $claim, $proof);
			}
		}
	}
	
	/**
	 * You may extend this method to allow handling of non-callable proof.
	 * 
	 * By default the auditor is not (fully) portable due to it's support for 
	 * callbacks. You may extend and invalidate this operation to force the 
	 * auditor to be portable; or allow only special protable proofs.
	 * 
	 * The Auditor allows for callable proofs to facilitate server side 
	 * checks; unlike the Validator class in the Auditor you should only place
	 * sanity checks as callables, all other user errors should be placed as
	 * using portable patterns so that the required information may be send 
	 * exported to the client.
	 */
	protected function addrule_with_proof($field, $claim, $proof)
	{
		$auditor = $this;
		$this->rules[] = function () use ($auditor, $field, $claim, $proof)
			{
				if ( ! $proof($auditor->fields, $field))
				{
					$auditor->adderror($field, $claim);
				}
			};
	}

	/**
	 * @return static $this
	 */
	function fields_array($fields)
	{
		$this->processedrules = false;
		return $this->trait_fields_array($fields);
	}

	/**
	 * @return static $this
	 */
	function check()
	{
		$this->compile_rules();		
		return $this->trait_check();
	}
	
	/**
	 * @return array
	 */
	function errors()
	{
		$this->compile_rules();
		return $this->trait_errors();
	}
	
	/**
	 * @return array
	 */
	function export()
	{
		$this->compile_rules();
		return [ 'rules' => $this->ruledef ];
	}
	
	/**
	 * Compiles rules into result.
	 */
	protected function compile_rules()
	{
		if ( ! $this->processedrules)
		{
			$this->errors = null;
			$this->ruledef = [];
			
			if ( ! empty($this->rules))
			{
				foreach ($this->rules as $rule_logic) 
				{
					$rule_logic();
				}
			}
			
			$this->processedrules = true;
		}
	}
	
} # class

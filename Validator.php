<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Validator extends \app\Instantiatable implements \mjolnir\types\Validator
{
	use \app\Trait_Validator;

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
	 * the proof is null the claim will provide the proof itself.
	 *
	 * Field and claim may be an array, and proof may be a function. The array
	 * version will always be translated down to the non-array version.
	 *
	 * A validator will run the rule as it's called and generate the result, as
	 * such it's designed for one time use but permits programatic rule
	 * definitions, ie. specifying proof as a boolean true or false. A
	 * Validator will also run on all fields.
	 *
	 * eg.
	 *
	 *	   // check a password is not empty
	 *     $validator->rule('password', 'not_empty');
	 *
	 *     // check both a password and title are not empty
	 *	   $validator->rule(['title', 'password'], 'not_empty');
	 *
	 *     // check a title is unique; two equivalent methods
	 *     $validator->rule('title', 'valid', ! static::exists($field['title'], 'title', $context));
	 *     $validator->test('title', ! static::exists($field['title'], 'title', $context));
	 *
	 *     // check multiple fields
	 *     $validator->rule
	 *         (
	 *             ['given_name', 'family_name'],
	 *             function ($fields, $field)) use ($context)
	 *             {
	 *                 return ! static::exists($fields[$field], $field, $context);
	 *             }
	 *         );
	 *
	 *
	 * @return static $this
	 */
	protected function addrule($field, $claim, $proof = null)
	{
		if ($proof === null)
		{
			$rules = \app\CFS::config('mjolnir/validator')['rules'];

			if ( ! $rules[$claim]($this->fields, $field))
			{
				$this->adderror($field, $claim);
			}
		}
		else if (\is_bool($proof))
		{
			if ( ! $proof)
			{
				$this->adderror($field, $claim);
			}
		}
		else # callback
		{
			if ( ! $proof($this->fields, $field))
			{
				$this->adderror($field, $claim);
			}
		}
	}

} # class

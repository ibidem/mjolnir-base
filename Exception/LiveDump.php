<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Exception
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Exception_LiveDump extends \app\Exception
{
	/** @var mixed variable to dump */
	protected $payload = null;

	/**
	 * @return static $this
	 */
	function variable_is($payload)
	{
		$this->payload = $payload;
		return $this;
	}

	/**
	 * @return mixed payload
	 */
	function variable()
	{
		return $this->payload;
	}

} # class

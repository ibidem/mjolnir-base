<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Assert extends \app\Instantiatable
{
	/**
	 * @var mixed
	 */
	protected $expected;
	
	/**
	 * @return \app\Assert
	 */
	static function instance($expected = null) 
	{
		$instance = parent::instance();
		$instance->expected = $expected;
		
		return $instance;
	}
	
	/**
	 * @return \app\Assert $this
	 * @throws \app\Exception
	 */
	function equals($actual)
	{
		if (\is_array($this->expected) !== \is_array($actual))
		{
			$this->assertFailed('Expected "'.$this->expected.'", got "'.$actual.'"');
		}
		else if (\is_array($this->expected))
		{
			if ($this->expected != $actual)
			{
				$this->assertFailed('Expected "'.$this->expected.'", got "'.$actual.'"');
			}
		}
		else # normal values
		{
			if ($this->expected !== $actual)
			{
				$this->assertFailed('Expected "'.$this->expected.'", got "'.$actual.'"');
			}
		}
		
		return $this;
	}
	
	/**
	 * @return \app\Assert $this
	 */
	function not_equals($actual)
	{
		try
		{
			$this->equals($actual);
		}
		catch (\app\Exception $e)
		{
			// good went as we wanted
			return $this;
		}
		
		// failed
		$this->assertFailed('Expected "'.$this->expected.'" to NOT equal "'.$actual.'"');
	}
	
	/**
	 * @throws \app\Exception
	 */
	protected function assertFailed($message)
	{
		throw new \app\Exception($message);
	}

} # class

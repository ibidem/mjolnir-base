<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class View extends \app\Instantiatable
	implements \kohana4\types\View,	\kohana4\types\FileBased
{
	use \app\Trait_FileBased;
	
	/**
	 * @var array
	 */
	protected $view_params = array();
	
	/**
	 * @see \kohana4\types\Instantiatable
	 * @return $this
	 */
	public static function instance($file = null)
	{
		$instance = parent::instance();
		$instance->file($file);
		return $instance;
	}
	
	/**
	 * @return string redered view
	 */
	public function render()
	{
		// extract view paramters into current scope as references to paramter
		// array in the view itself, skipping over already defined variables
		\extract($this->view_params, EXTR_REFS);
		
		// start capture
		\ob_start();
		try
		{
			// include in current scope
			include $this->file;
		}
		catch (\Exception $error)
		{
			// cleanup
			\ob_end_clean();
			// re-throw
			throw $error;
		}
		// success
		return \ob_get_clean();
	}
	
	/**
	 * @param string valid PHP variable name
	 * @param mixed variable to bind
	 * @return $this
	 */
	public function bind($name, & $variable)
	{
		$this->view_params[$name] &= $variable;
		return $this;
	}
	
	/**
	 * @param string valid PHP variable name
	 * @param mixed value to set
	 * @return $this
	 */
	public function constant($name, $value)
	{
		$this->view_params[$name] = $value;
		return $this;
	}
	
	/**
	 * @deprecated use render always; so exceptions will work properly
	 */
	public final function __toString()
	{
		// views may contain logic, by allowing __toString not only does 
		// Exception handling become unnecesarily complicated because of how
		// this special method can't throw exceptions, it also ruins the entire
		// stack by throwing the exception in a completely undefined manner, 
		// ie. whenever it decides to convert to a string. It's not worth it.
		\app\Layer::get_top()->exception
			(
				\app\Exception_NotApplicable::instance
					('Casting to string not allowed for Views.')
			);
	}
	
} # class

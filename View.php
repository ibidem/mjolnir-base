<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class View extends \app\Instantiatable
	implements
		\mjolnir\types\View,
		\mjolnir\types\FileBased,
		\mjolnir\types\Renderable
{
	/**
	 * @var array
	 */
	protected $view_params = array();

	/**
	 * @see \mjolnir\types\Instantiatable
	 * @return \mjolnir\base\View
	 */
	static function instance($file = null, $ext = EXT)
	{
		$instance = parent::instance();
		if ($file !== null)
		{
			$instance->file($file, $ext);
		}
		return $instance;
	}

	/**
	 * @return string redered view
	 */
	function render()
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
	 * @param array array to bind
	 * @return \mjolnir\base\View $this
	 */
	function bind($name, array & $array)
	{
		$this->view_params[$name] =& $array;
		return $this;
	}

	/**
	 * @param string valid PHP variable name
	 * @param mixed value to set
	 * @return \mjolnir\base\View $this
	 */
	function variable($name, $value)
	{
		$this->view_params[$name] = $value;
		return $this;
	}

	/**
	 * @deprecated use render always; so exceptions will work properly
	 */
	final function __toString()
	{
		// views may contain logic, by allowing __toString not only does
		// Exception handling become unnecesarily complicated because of how
		// this special method can't throw exceptions, it also ruins the entire
		// stack by throwing the exception in a completely undefined manner,
		// ie. whenever it decides to convert to a string. It's not worth it.
		\app\Layer::get_top()->exception
			(
				new \app\Exception
					('Casting to string not allowed for Views.'),
				true # no throw
			);
	}

# FileBased trait

	/**
	 * @var string view file
	 */
	protected $file;

	/**
	 * @param string file
	 * @return \mjolnir\base\View $this
	 */
	function file($file, $ext = EXT)
	{
		$file = 'views'.DIRECTORY_SEPARATOR.$file;
		$file_path = \app\CFS::file($file, $ext);
		// found file?
		if ($file_path === null)
		{
			throw new \app\Exception
				("Required file [$file] not found.");
		}
		else # found file
		{
			$this->file = $file_path;
		}

		return $this;
	}

	/**
	 * @param string explicit file path
	 * @return \mjolnir\base\View $this
	 */
	function file_path($file)
	{
		$this->file = \realpath($file);
		if ($file !== null && ! \file_exists($this->file))
		{
			throw new \app\Exception
				("Required file [$file] not found.");
		}

		return $this;
	}

	/**
	 * @return string file path
	 */
	function get_file()
	{
		return $this->file;
	}

# /FileBased trait

} # class

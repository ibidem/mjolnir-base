<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Pager extends \app\Instantiatable
	implements \ibidem\types\Renderable
{
	/**
	 * @var string 
	 */
	private $base_url;
	
	/**
	 * @param string base_url
	 * @return \ibidem\base\Pager 
	 */
	public function base_url($base_url)
	{
		$this->base_url = $base_url;
		return $this;
	}
	
	/**
	 * @param string absolute path
	 * @return \ibidem\base\Pager 
	 */
	public function view($absolute_path)
	{
		$this->view = $absolute_path;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function render()
	{
		// @todo render \ibidem\base\Pager
		return '[todo pager]';
	}
	
	/**
	 * @deprecated use render always; so exceptions will work properly
	 */
	public final function __toString()
	{
		// pagers may contain logic, by allowing __toString not only does 
		// Exception handling become unnecesarily complicated because of how
		// this special method can't throw exceptions, it also ruins the entire
		// stack by throwing the exception in a completely undefined manner, 
		// ie. whenever it decides to convert to a string. It's not worth it.
		\app\Layer::get_top()->exception
			(
				\app\Exception_NotApplicable::instance
					('Casting to string not allowed for Pagers.')
			);
	}

} # class

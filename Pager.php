<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Pager extends \app\Instantiatable
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
	
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (\Exception $e)
		{
			return '[ERROR: '.$e->getMessage().']';
		}
	}

} # class

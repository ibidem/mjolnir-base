<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2013 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ViewComposite extends \app\Instantiatable implements \mjolnir\types\View
{
	use \app\Trait_View;

	/**
	 * @var array
	 */
	protected $views = null;

	/**
	 * @return static $this
	 */
	function views_are(array $views)
	{
		$this->views = $views;
		return $this;
	}

	// ------------------------------------------------------------------------
	// interface: Renderable

	/**
	 * @return string
	 */
	function render()
	{
		$view = '';
		if ($this->views !== null)
		{
			foreach ($this->views as $view)
			{
				$view->inherit($this);
				$view .= $view->render();
			}
		}

		return $view;
	}

} # class

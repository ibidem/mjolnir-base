<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Pager extends \app\Instantiatable
	implements 
		\ibidem\types\Renderable, 
		\ibidem\types\FileBased,
		\ibidem\types\Pager
{	
	/**
	 * @var array 
	 */
	private $options = array
		(
			'currentpage' => null,  # selected page
			'has_ruler'   => null,  # show ruler on pager
			'order'       => \ibidem\types\Pager::ascending,
		
			'lang' => array
				(
					'entries_to_entries' => 'Entries :number to :number_end.',
					'bookmark_at_entry' => 'Bookmark at Entry #:bookmark.',
					'page_x' => 'Entry :number.',
					'page_x_bookmarked' => 'Entry :number. Bookmarked.',
				),
		
			'bookmark' => array
				(
					'anchor' => null,
					'page'   => 0,
					'entry'  => 0,
				),
		
			'buttons' => array
				(
					'next' => 'Next',
					'prev' => 'Previous'
				),
		
			// computed
			'pagecount' => 0,
			'startpoint' => 0,
			'endpoint' => 0,
			'startellipsis' => 0,
			'endellipsis' => 0,
		
			'show_jumpto' => true,
			'show_pageindex' => true,
		);
	
	/**
	 * @param integer totalitems
	 * @param integer url_base
	 * @param integer pagerdiff
	 * @param integer pagelimit 
	 * @return \ibidem\base\Pager instance
	 */
	public static function instance($totalitems = 0, $url_base = '', $pagediff = 4, $pagelimit = 20)
	{
		$instance = parent::instance();
		$instance->options['totalitems'] = $totalitems;
		$instance->options['url_base'] = $url_base;
		$instance->options['pagediff'] = $pagediff;
		$instance->options['pagelimit'] = $pagelimit;
		
		return $instance;
	}
	
	/**
	 * @param boolean settings
	 * @return \ibidem\base\Pager $this
	 */
	public function show_jumpto($setting)
	{
		$this->options['show_jumpto'] = $setting;
		return $this;
	}
	
	/**
	 * @param boolean settings
	 * @return \ibidem\base\Pager $this
	 */
	public function show_pageindex($settings)
	{
		$this->options['show_pageindex'] = $settings;
		return $this;
	}
	
	/**
	 * @param array buttons 
	 * @return \ibidem\base\Pager $this
	 */
	public function buttons(array $buttons)
	{
		foreach ($buttons as $button => $text)
		{
			$this->options['buttons'][$button] = $text;
		}
		
		return $this;
	}
		
	/**
	 * @param integer page
	 * @return \ibidem\base\Pager $this 
	 */
	public function currentpage($page)
	{
		$this->options['currentpage'] = $page;
		return $this;
	}
	
	/**
	 * @param integer page
	 * @return \ibidem\base\Pager $this 
	 */
	public function bookmark($page)
	{
		$this->options['bookmark']['entry'] = $page;
		return $this;
	}
	
	/**
	 * Generic anchor; for example, in the case of html this will result in
	 * a '#'.$anchor in the url; other systems might behave differently.
	 * 
	 * @param string anchor
	 * @return \ibidem\types\Pager $this
	 */
	function bookmark_anchor($anchor)
	{
		$this->options['bookmark']['anchor'] = $anchor;
		return $this;
	}
	
	/**
	 * @param string plural version
	 * @param string singular version; assumed plural if not specified
	 * @return \ibidem\base\Pager $this
	 */
	public function lang(array $lang)
	{
		foreach ($lang as $key => $lang)
		{
			$this->options['lang'][$key] = $lang;
		}
		
		return $this;
	}

	/**
	 * @param string order
	 * @return \ibidem\types\Pager $this
	 */
	public function order($order)
	{
		$this->options['order'] = $order;
		return $this;
	}
	
	/**
	 * @param integer total items
	 * @return \ibidem\base\Pager $this
	 */
	public function totalitems($totalitems)
	{
		$this->options['totalitems'] = $totalitems;
		return $this;
	}		
	
	/**
	 * @param string base_url
	 * @return \ibidem\base\Pager $this
	 */
	public function url_base($url_base)
	{
		$this->options['url_base'] = $url_base;
		return $this;
	}

	/**
	 * @param integer pagediff
	 * @return \ibidem\base\Pager $this 
	 */
	public function pagediff($pagediff)
	{
		$this->options['pagediff'] = $pagediff;
		return $this;
	}
	
	/**
	 * @param integer pagelimit
	 * @return \ibidem\base\Pager $this
	 */
	public function pagelimit($pagelimit)
	{
		$this->options['pagelimit'] = $pagelimit;
		return $this;
	}
	
	/**
	 * Setup pager 
	 */
	private function setup()
	{
		// is file set?
		if ( ! $this->get_file())
		{
			$pager_config = \app\CFS::config('ibidem/pager');
			$this->file($pager_config['view.default']);
		}
		
		// extract options to work in context
		\extract($this->options, EXTR_REFS);
		
		// is the ruler showed?
		$has_ruler !== null or $has_ruler = (empty($currentpage) ? false : true);
	
		// calculate page count
		$pagecount = \ceil($totalitems / $pagelimit);
		
		// do we have a bookmark?
		if ($bookmark['entry'] != 0)
		{
			$bookmark['page'] = \ceil($bookmark['entry'] / $pagelimit);
		}
		
		$startpoint = ($pagecount < $pagediff ? $pagecount : $pagediff);
		$endpoint = ($pagecount - $pagediff + 1 < 1 ? 1 : $pagecount - $pagediff + 1);
		
		$startellipsis = $startpoint;
		$endellipsis = $endpoint;
		
		if ($endellipsis == $bookmark['page'])
		{
			$endellipsis -= 1;
		}
		
		if ($startpoint >= $currentpage - $pagediff)
		{
			$startpoint = $pagediff * 3 + 1 - 3;
			$startellipsis = 0;
		}
		
		if ($endpoint <= $currentpage + $pagediff)
		{
			$endpoint = $pagecount - ($pagediff * 3 - 3);
			$endellipsis = 0;
		}
	}
	
	/**
	 * @return string
	 */
	public function render()
	{		
		// setup pager
		$this->setup();
		
		// extract view paramters into current scope as references to paramter
		// array in the view itself, skipping over already defined variables
		\extract($this->options, EXTR_REFS);
		
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
	
# FileBased trait
	
	/**
	 * @var string view file
	 */
	protected $file;
		
	/**
	 * @param string file 
	 * @return \ibidem\base\View $this
	 */
	public function file($file)
	{
		$file_path = \app\CFS::file($file);
		// found file?
		if ($file_path === null)
		{
			throw new \app\Exception_NotFound
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
	 * @return \ibidem\base\View $this
	 */
	public function file_path($file)
	{
		$this->file = \realpath($file);
		if ($file !== null && ! \file_exists($this->file))
		{
			throw new \app\Exception_NotFound
				("Required file [$file] not found.");
		}
		
		return $this;
	}
	
	/**
	 * @return string file path
	 */
	public function get_file()
	{
		return $this->file;
	}
	
# /FileBased trait

} # class

<?php namespace kohana4\base;

/** 
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Layer_HTML extends \app\Layer 
	implements 
		\kohana4\types\Meta,
		\kohana4\types\Document,
		\kohana4\types\HTML
{
	use \app\Trait_Meta;
	use \app\Trait_Document;
	
	/**
	 * @var string
	 */
	protected static $layer_name = 'html';
	
	/**
	 * @return \kohana4\types\Layer
	 */
	public static function instance()
	{
		$instance = parent::instance();
		$instance->meta = \app\CFS::config('kohana4/html');
		return $instance;
	}

	/**
	 * @return string
	 */
	protected function html_before()
	{
		$html_before = $this->meta['doctype']."\n";
		// appcache manifest
		if ($this->meta['appcache'] !== null)
		{
			$html_before .= '<html manifest="'.$this->meta['appcache'].'">';
		}
		else # no appcache
		{
			$html_before .= '<html>';
		}
		// head section
		$html_before .= '<head>';
		// load base configuration
		$kohana4_base = \app\CFS::config('kohana4/base');
		
		# --- Relevant to the user experience -------------------------------- #

		// content type
		$html_before .= '<meta http-equiv="content-type" content="'
			. Layer::find('http')->get_content_type()
			. '; charset='.$kohana4_base['charset'].'">';
		// Make a DNS handshake with a foreign domain, so the connection goes 
		// faster when the user eventually needs to access it.
		// eg. //ajax.googleapis.com 
		foreach ($this->meta['prefetch_domains'] as $prefetch_domain)
		{
			'<link rel="dns-prefetch" href="'.$prefetch_domain.'">';
		}
		// mobile viewport optimized: h5bp.com/viewport
		$html_before .= '<meta name="viewport" content="width=device-width">';
		// helps a little with compatibility; unnecesary \w .htaccess
		$html_before .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		// standard favicon path
		if ($this->meta['favicon'] === null)
		{
			$html_before .= '<link rel="shortcut icon" href="'.$kohana4_base['url_base'].'favicon.ico" type="image/x-icon">';
		}
		else # predefined path
		{
			$html_before .= '<link rel="shortcut icon" href="'.$this->meta['favicon'].'" type="image/x-icon">';
		}
		// title
		$html_before .= '<title>'.$this->meta['title'].'</title>';
		// stylesheets
		foreach ($this->meta['stylesheets'] as $style)
		{
			$html_before .= '<link rel="stylesheet" type="'.$style['type'].'" href="'.$style['href'].'">';
		}
		// kill IE6's pop-up-on-mouseover toolbar for images
		$html_before .= '<meta http-equiv="imagetoolbar" content="false">';
		
		# --- Relevant to search engine results ------------------------------ #
		
		if ($this->meta['description'] !== null)
		{
			// note: it is not guranteed search engines will use it; and they
			// won't if the content of the page is nonexistent, or this 
			// description is not unique enough over multiple pages.
			$html_before .= '<meta name="description" content="'.$this->meta['description'].'">';
		}
		
		// extra garbage: keywords, generator, author
		if ( ! empty($this->meta['keywords']))
		{
			$keywords = '';
			foreach ($this->meta['keywords'] as $keyword)
			{
				$keywords .= ' '.$keyword;
			}
			$html_before .= '<meta name="keywords" content="'.$keywords.'">';
		}
		if ($this->meta['generator'] !== null)
		{
			$html_before .= '<meta name="generator" content="'.$this->meta['generator'].'">';
		}
		if ($this->meta['author'] !== null)
		{
			$html_before .= '<meta name="author" content="'.$this->meta['author'].'">';
		}
		
		# --- Relevant to crawlers ------------------------------------------- #
		
		// A canonical route is the route by which search engines should  
		// identify the current page; ragerdless of what the current url might 
		// look like.
		if ($this->meta['canonical'] !== null)
		{
			$html_before .= '<link rel="canonical" href="'.$this->meta['canonical'].'">';
		}
		
		// sitemap, for search engines. 
		// see: http://www.sitemaps.org/protocol.html 
		if ($this->meta['sitemap'] !== null)
		{
			$html_before .= '<link rel="sitemap" type="application/xml" title="Sitemap" href="'.$this->meta['sitemap'].'">';
		}
		
		// block search engines from viewing the page
		if ($this->meta['crawlers'])
		{
			$html_before .= '<meta name="robots" content="index, follow" />';
		}
		else # do not allow search engines 
		{
			$html_before .= '<meta name="robots" content="noindex" />';
		}
		
		# --- Feed and callbacks --------------------------------------------- #

		// http://www.rssboard.org/rss-specification
		if ($this->meta['rssfeed'] !== null)
		{
			$html_before .= '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$this->meta['rssfeed'].'">';
		}
		
		// http://www.atomenabled.org/developers/syndication/
		if ($this->meta['atomfeed'] !== null)
		{
			$html_before .= '<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$this->meta['atomfeed'].'">';
		}
		
		// http://codex.wordpress.org/Introduction_to_Blogging#Pingbacks
		if ($this->meta['pingback'] !== null)
		{
			$html_before .= '<link rel="pingback" href="'.$this->meta['pingback'].'">';
		}
		
		# --- Extras --------------------------------------------------------- #

		// see: http://humanstxt.org/
		if ($this->meta['humanstxt'])
		{
			$html_before .= '<link type="text/plain" rel="author" href="'.$kohana4_base['base_url'].'humans.txt">';
		}
			
		# Pin status (IE9 etc)
		
		// name to use when pinned
		if ($this->meta['application_name'] !== null)
		{		
			$html_before .= '<meta name="application-name" content="'.$this->meta['application_name'].'">';
		}
		
		// tooltip to use when pinned
		if ($this->meta['application_tooltip'] !== null)
		{
			$html_before .= '<meta name="msapplication-tooltip" content="'.$this->meta['application_tooltip'].'">';
		}
		
		// page to go to when pinned
		if ($this->meta['application_starturl'] !== null)
		{
			$html_before .= '<meta name="msapplication-starturl" content="'.$this->meta['application_starturl'].'">';
		}
		
		// close head section
		$html_before .= '</head><body>';
		// css switch for more streamline style transitions
		if ($this->meta['javascript_switch'])
		{
			$html_before .= '<script type="text/javascript">document.body.id = "javascript-enabled";</script>';
		}
		$html_before .= "\n\n";
  
		return $html_before;
	}
	
	/**
	 * Closing html. 
	 */
	protected function html_after()
	{
		$body = "\n\n";
		foreach ($this->meta['scripts'] as $script)
		{
			$body .= '<script src="'.$script.'"></script>';
		}
		$body .= "</body></html>\n";
		
		return $body;
	}
	
	/**
	 * Execute the layer.
	 */
	public function execute()
	{
		try
		{
			// execute sub layers
			parent::execute();
			// got sublayers?
			if ($this->layer !== null)
			{
				$layer_contents = $this->layer->get_contents();
				if ($layer_contents !== null)
				{
					$this->contents
						(
							static::html_before().
							$layer_contents.
							static::html_after()
						);
				}
				else # no layer contents
				{
					// we still output a valid html document; it's possible it's 
					// just all javascript or something else, so there's no contents 
					// becuase it's all dynamically generated
					$this->contents
						(
							static::html_before().
							static::html_after()
						);
				}
			}
			else # we've only got a body
			{
				// if we're a leaf layer, we use the body
				$this->contents
					(
						static::html_before().
						$this->get_body().
						static::html_after()
					);
			}
		}
		catch (\Exception $exception)
		{
			$this->exception($exception, true);
		}
	}
	
	/**
	 * Fills body and approprite calls for current layer, and passes the 
	 * exception up to be processed by the layer above, if the layer has a 
	 * parent.
	 * 
	 * @param \Exception
	 */
	public function exception(\Exception $exception, $origin = false)
	{
		if (\is_a($exception, '\\kohana4\\types\\Exception'))
		{
			$this->title($exception->title());
			$this->crawlers(false);
			if ($this->layer !== null && ! $origin)
			{
				$this->contents
					(
						$this->html_before().
						$this->layer->get_contents().
						$this->html_after()
					);
			}
			else if ( ! $origin)
			{
				$this->contents
					(
						$this->html_before().
						$this->get_body().
						$this->html_after()
					);
			}
		}
		
		// default execution from Layer
		parent::exception($exception);
	}
	
	/**
	 * Set the document's body.
	 * 
	 * @param string document body
	 * @return $this
	 */
	public function body($body)
	{
		if ($this->layer !== null)
		{
			throw \app\Exception_NotApplicable::instance("Can't have both a body and contents.");
		}
		
		$this->body = $body;
		return $this;
	}
	
	/**
	 * Sets the doctype. See: \kohana4\types\HTML for constants.
	 * 
	 * @param string doctype
	 * @return $this
	 */
	public function doctype($doctype)
	{
		return $this->meta('doctype', $doctype);
	}
	
	/**
	 * Appcache manifest location.
	 * 
	 * @param string url
	 * @return $this
	 */
	public function appcache($url = null)
	{
		return $this->meta('appcache', $url);
	}
	
	/**
	 * Sitemap, be it index or simple sitemap.
	 * 
	 * @param string url
	 * @return $this
	 */
	public function sitemap($url = null)
	{
		return $this->meta('sitemap', $url);
	}
	
	/**
	 * @param array domains
	 * @return $this
	 */
	public function add_dns_prefetch_domains(array $domains)
	{
		foreach ($domains as $domain)
		{
			$this->meta['prefetch_domains'][] = $domain;
		}
		
		return $this;
	}
	
	/**
	 * @param string favicon uri
	 * @return $this
	 */
	public function favicon($url = null)
	{
		return $this->meta('favicon', $url);
	}
	
	/**
	 * @param string title 
	 * @return $this
	 */
	public function title($title)
	{
		return $this->meta('title', $title);
	}
	
	/**
	 * @param string
	 * @return $this 
	 */
	public function add_stylesheet($href, $type = "text/css")
	{
		$this->meta['stylesheets'][] = array('href' => $href, 'type' => $type);
		return $this;
	}
	
	/**
	 * @param string
	 * @return $this 
	 */
	public function add_script($src)
	{
		$this->meta['scripts'][] = $src;
		return $this;
	}
	
	/**
	 * @param string description 
	 * @return $this
	 */
	public function description($desc = null)
	{
		return $this->meta('description', $desc);
	}
	
	/**
	 * @param array new keywards
	 * @return $this
	 */
	public function add_keywords(array $keywords)
	{
		foreach ($keywords as $keyword)
		{
			$this->meta['keywords'][] = $keyword;
		}
		
		return $this;
	}
	
	/**
	 * @param string canonical url 
	 * @return $this
	 */
	public function canonical($url = null)
	{
		return $this->meta('canonical', $url);
	}
	
	/**
	 * @param boolean enabled?
	 * @return $this
	 */
	public function crawlers($enabled = true)
	{
		return $this->meta('crawlers', $enabled);
	}
	
	/**
	 * @param string url
	 * @return $this
	 */
	public function rssfeed($url = null)
	{
		return $this->meta('rssfeed', $url);
	}
	
	/**
	 * @param string url
	 * @return $this
	 */
	public function atomfeed($url = null)
	{
		return $this->meta('atomfeed', $url);
	}
	
	/**
	 * @param string url
	 * @return $this
	 */
	public function pingback($url = null)
	{
		return $this->meta('pingback', $url);
	}
	
	/**
	 * @param boolean enabled?
	 * @return $this 
	 */
	public function humanstxt($enabled = true)
	{
		return $this->meta('humanstxt', $enabled);
	}
	
	/**
	 * Metadata for application running as desktop.
	 * 
	 * @param string name
	 * @return $this
	 */
	public function application_name($name = null)
	{
		return $this->meta('application_name', $name);
	}
	
	/**
	 * Metadata for application running as desktop.
	 * 
	 * @param string tooltip
	 * @return $this
	 */
	public function application_tooltip($tooltip = null)
	{
		return $this->meta('application_tooltip', $tooltip);
	}
	
	/**
	 * Metadata for application running as desktop.
	 * 
	 * @param string starturl
	 * @return $this
	 */
	public function application_starturl($starturl = null)
	{
		return $this->meta('application_starturl', $starturl);
	}
	
} # class

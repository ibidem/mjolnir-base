<?php namespace ibidem\base;

/**
 * Make is a library that handles mockup. The Mockup class is reserved by the 
 * actual Mockup module for the project.
 * 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Make extends \app\Instantiatable
{
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var array
	 */
	protected $args;
	
	/**
	 * @var array 
	 */
	protected static $counters = array();
	
	/**
	 * @param string type 
	 * @return \ibidem\base\Make
	 */
	public static function instance($type = 'paragraph', array $args = null)
	{
		$instance = parent::instance();
		$instance->type = $type;
		$instance->args = $args;
		return $instance;
	}
	
	/**
	 * Time from begining to, 5 years in the future.
	 * 
	 * @return integer
	 */
	public static function time()
	{
		return \rand(0, \time() + \ibidem\types\Date::year * 5);
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function name()
	{
		return static::instance('name');
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function telephone()
	{
		return static::instance('telephone');
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function url()
	{
		return static::instance('url');
	}
	
	/**
	 * @return \ibidem\base\Make 
	 */
	public static function counter($id)
	{
		return static::instance('counter', \func_get_args());
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function title()
	{
		return static::instance('title');
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function word()
	{
		return static::instance('word');
	}
	
	/**
	 * @param mixed source
	 * @param integer copies
	 * @return array
	 */
	public static function copies($source, $count = 25)
	{
		$copies = array();
		while ($count-- > 0)
		{
			$copies[] = $source;
		}
		
		return $copies;
	}
	
	/**
	 * @return string 
	 */
	public function render()
	{
		$mockup = \app\CFS::config('ibidem\mockup');
		switch ($this->type)
		{
			case 'name':
				// names are two words
				$family_name = self::random($mockup['family_names']);
				$given_name = self::random($mockup['given_names']);
				return $given_name.' '.$family_name;

			case 'title':
				$words = \rand(4, 20);
				$title = \ucfirst($mockup['words'][\rand(1, \count($mockup['words']) - 1)]);

				while ($words-- > 0)
				{
					$title .= ' '.$mockup['words'][\rand(1, \count($mockup['words']) - 1)];
				}

				return $title;

			case 'word':
				return $mockup['words'][\rand(1, \count($mockup['words']) - 1)];

			case 'url':
				// we just need a unique one
				return '//localhost/'.\rand(1, 999999999);

			case 'counter':
				$id = $this->args[0];
				// gurantee counter is initialized
				isset(static::$counters[$id]) or static::$counters[$id] = 1;
				return ''.static::$counters[$id]++;

			case 'telephone':
				return '('.\rand(111, 999).') '.\rand(111, 999).'-'.\rand(1111, 9999);

			case 'paragraph':
			default:
				$sentences = \rand(5, 15);
				$paragraph = '';
				while ($sentences-- > 0)
				{
					$words = \rand(4, 20);
					$sentence = \ucfirst($mockup['words'][\rand(1, \count($mockup['words']) - 1)]);

					while ($words-- > 0)
					{
						$sentence .= ' '.$mockup['words'][\rand(1, \count($mockup['words']) - 1)];
					}

					$sentence .= $mockup['punctuation'][\rand(1, \count($mockup['punctuation']) - 1)];
					$sentence .= '  ';
					$paragraph .= $sentence;
				}
				return $paragraph;
		}
	}
	
	/**
	 * @return string
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (\Exception $e)
		{
			// [!!] __toString can not throw exception in PHP!
			return '[ERROR: '.$e->getMessage().']';
		}
	}
	
	/**
	 * @return mixed
	 */
	private function random(array $collection)
	{
		return $collection[\rand(0, \count($collection) - 1)];
	}

} # class

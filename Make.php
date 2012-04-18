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
	public static function email()
	{
		return static::instance('email');
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function ssn()
	{
		return static::instance('ssn');
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function address()
	{
		return static::instance('address');
	}
	
	public static function city()
	{
		return static::instance('city');
	}
	
	/**
	 * @return \ibidem\base\Make
	 */
	public static function paragraph()
	{
		return static::instance('paragraph');
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
	 * @param integer count
	 * @return \ibidem\base\Make
	 */
	public static function words($count = 10)
	{
		return static::instance('words', \func_get_args());
	}
	
	/**
	 * @param mixed source
	 * @param integer copies
	 * @return array
	 */
	public static function copies($source, $count = null)
	{
		$count !== null or $count = \rand(5, 25);
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
				
			case 'words':
				$count = $this->args[0];
				$words = '';
				while ($count-- > 0)
				{
					$words .= self::random($mockup['words']).' ';
				}
				return \rtrim($words, ' ');

			case 'telephone':
				return '('.\rand(111, 999).') '.\rand(111, 999).'-'.\rand(1111, 9999);
				
			case 'city':
				return self::random($mockup['cities']);
				
			case 'email':
				return \strtolower(self::random($mockup['given_names'])).'@'.self::random(array('gmail', 'yahoo', 'hotmail')).'.com';
				
			case 'ssn':
				$month = \rand(1, 12);
				$day = \rand(1, 30);
				$sector = \rand(1, 52);
				return ''.\rand(1, 9).\rand(0, 99)
					. ($month < 10 ? '0'.$month : $month)
					. ($day < 10 ? '0'.$day : $day)
					. ($sector < 10 ? '0'.$sector : $sector);
				
			case 'address':
				return 'Str. '.self::random($mockup['family_names'])
					. ', Nr. '.\rand(11, 35)
					. ', Bl. '.\rand(51, 956)
					. ', Ap. '.\rand(1, 25)
					. ', '.self::random($mockup['cities'])
					;

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
	private static function random(array $collection)
	{
		return $collection[\rand(0, \count($collection) - 1)];
	}

} # class

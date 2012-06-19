<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class DateFormatter extends \app\Instantiatable
{
	/**
	 * @var string
	 */
	private $format = 'Y-m-d H:i';
	
	/**
	 * Fallback date format.
	 * 
	 * @param string format
	 * @return \ibidem\base\DateFormatter $this
	 */
	public function fallback($format)
	{
		$this->format = $format;
		return $this;
	}
	
	/**
	 * @param \DateTime datetime
	 * @param array filter
	 * @return string formatted date (may not be valid date string)
	 */
	public function relative(\DateTime $datetime, array $filter = null)
	{
		$relative_dates = \app\CFS::config('ibidem/dateformatter');
		
		if ($filter !== null) 
		{
			foreach ($filter as $key)
			{
				$callback = $relative_dates[$key];
				if ($callback($datetime))
				{
					return \app\Lang::msg('DateFormatter:'.$key);
				}
			}
		}
		else # null filter
		{
			foreach ($relative_dates as $key => $callback)
			{
				if ($callback($datetime))
				{
					return \app\Lang::msg('DateFormatter:'.$key);
				}
			}
		}
		
		// no matches, fallback
		return $datetime->format($this->format);
	}

} # class
<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 * @see \ibidem\base\DateFormatter
 */
class DateFormatterTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function relative()
	{
		$lang = \app\Lang::get_lang();
		\app\Lang::lang('en-us');
		$datetime = new \DateTime('today');
		$formatter = DateFormatter::instance()->fallback('Y-m-d');
		$today = $formatter->relative($datetime, array('today'));
		$this->assertEquals('today', $today);
		$this->assertEquals($datetime->format('Y-m-d'), $formatter->relative($datetime, array()));
		\app\Lang::lang($lang);
	}

} # class

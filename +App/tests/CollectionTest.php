<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Library
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	function implode()
	{
		$result = \app\Collection::implode(', ', array( 1 => 'a', 2 => 'b', 3 => 'c'), function ($key, $value) {
			return '['.$value.$key.']';
		});
		$this->assertEquals('[a1], [b2], [c3]', $result);
	}

} # class

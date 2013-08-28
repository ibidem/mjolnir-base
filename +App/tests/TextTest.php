<?php namespace mjolnir\base\tests;

use \mjolnir\base\Text;

class CVSRenderableTester implements \mjolnir\types\Renderable
{
	use \app\Trait_Renderable;

	public $value = null;

	function __construct($value)
	{
		$this->value = $value;
	}

	function render()
	{
		return $this->value;
	}
}

class TextTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Text'));
	}

	/** @test */ function
	cvs_works_on_simple_array()
	{
		$array = array
			(
				[ 'id' => 1, 'title' => 'Eve',   'magicpower' => 50 ],
				[ 'id' => 2, 'title' => 'Alice', 'magicpower' => 5 ],
				[ 'id' => 3, 'title' => 'Bob',   'magicpower' => 10 ],
			);

		$rd = PHP_EOL; # row devider

		$this->assertEquals
			(
				Text::csv($array),
				"1,Eve,50{$rd}2,Alice,5{$rd}3,Bob,10"
			);
	}

	/** @test */ function
	cvs_works_with_custom_dividers()
	{
		$array = array
			(
				[ 'id' => 1, 'title' => 'Eve',   'magicpower' => 50 ],
				[ 'id' => 2, 'title' => 'Alice', 'magicpower' => 5 ],
				[ 'id' => 3, 'title' => 'Bob',   'magicpower' => 10 ],
			);

		$rd = '/'; # row devider

		$this->assertEquals
			(
				Text::csv($array, '/', '-'),
				"1-Eve-50{$rd}2-Alice-5{$rd}3-Bob-10"
			);
	}

	/** @test */ function
	cvs_works_on_renderable()
	{
		$array = array
			(
				[ 'id' => 1, 'title' => new CVSRenderableTester('Eve'),   'magicpower' => 50 ],
				[ 'id' => 2, 'title' => 'Alice', 'magicpower' => 5  ],
				[ 'id' => 3, 'title' => new CVSRenderableTester('Bob'),   'magicpower' => 10 ],
			);

		$rd = PHP_EOL; # row devider

		$this->assertEquals
			(
				Text::csv($array),
				"1,Eve,50{$rd}2,Alice,5{$rd}3,Bob,10"
			);
	}

	/** @test */ function
	cvs_works_on_datetime()
	{
		$date = \date_create('now');
		$strdate = $date->format(\app\Lang::key('mjolnir:csv/datetime'));

		$array = array
			(
				[ 'id' => 1, 'title' => \date_create('now') ],
				[ 'id' => 2, 'title' => 'Alice' ],
			);

		$rd = PHP_EOL; # row devider

		$this->assertEquals
			(
				Text::csv($array),
				"1,{$strdate}{$rd}2,Alice"
			);
	}

	/** @test */ function
	summary_works_on_simple_string()
	{
		$test = 'Anna broke Eves kitches, and gaming console.';

		$this->assertEquals
			(
				Text::summary($test, 17),
				'Anna broke Eve...'
			);
	}

	/** @test */ function
	summary_custom_cutoff_works()
	{
		$test = 'Anna broke Eves kitches, and gaming console.';

		$this->assertEquals
			(
				Text::summary($test, 15, '~'),
				'Anna broke Eve~'
			);
	}

	/** @test */ function
	summary_cutoff_text_doesnt_show_if_source_is_short()
	{
		$test = 'Anna broke Eve';

		$this->assertEquals
			(
				Text::summary($test, 14),
				'Anna broke Eve'
			);
	}

	/** @test */ function
	summary_doesnt_show_for_short_source()
	{
		$test = 'Anna broke Eve';

		$this->assertEquals
			(
				Text::summary($test, 42),
				'Anna broke Eve'
			);
	}

	/** @test */ function
	baseindent_works_on_simple_string_and_defaults()
	{
		$dir = \app\CFS::dir('tests/+Assets/mjolnir.base');
		$sample = \app\Filesystem::gets($dir.'text.baseindent.1.before');
		$result = \app\Filesystem::gets($dir.'text.baseindent.1.after');

		if ($sample == null || $result == null)
		{
			throw new \Exception('Missing Sample Files');
		}

		$this->assertEquals(Text::baseindent($sample), $result);
	}

	/** @test */ function
	baseindent_works_on_simple_string_and_no_zero_indent()
	{
		$dir = \app\CFS::dir('tests/+Assets/mjolnir.base');
		$sample = \app\Filesystem::gets($dir.'text.baseindent.2.before');
		$result = \app\Filesystem::gets($dir.'text.baseindent.2.after');

		if ($sample == null || $result == null)
		{
			throw new \Exception('Missing Sample Files');
		}

		$this->assertEquals(Text::baseindent($sample, '', 4, false), $result);
	}

	/** @test */ function
	baseindent_works_on_simple_string_and_custom_indent()
	{
		$dir = \app\CFS::dir('tests/+Assets/mjolnir.base');
		$sample = \app\Filesystem::gets($dir.'text.baseindent.3.before');
		$result = \app\Filesystem::gets($dir.'text.baseindent.3.after');

		if ($sample == null || $result == null)
		{
			throw new \Exception('Missing Sample Files');
		}

		$this->assertEquals(Text::baseindent($sample), $result);
	}

	/** @test */ function
	baseindent_works_on_string_with_middle_indent()
	{
		$dir = \app\CFS::dir('tests/+Assets/mjolnir.base');
		$sample = \app\Filesystem::gets($dir.'text.baseindent.4.before');
		$result = \app\Filesystem::gets($dir.'text.baseindent.4.after');

		if ($sample == null || $result == null)
		{
			throw new \Exception('Missing Sample Files');
		}

		$this->assertEquals(Text::baseindent($sample), $result);
	}

	/** @test */ function
	breaks_to_html_works_on_simple_string()
	{
		$this->assertEquals
			(
				Text::breaks_to_html("test\r\ntest\rtest\ntest"),
				'test<br/>test<br/>test<br/>test'
			);
	}

	/** @test */ function
	lpad_works_on_basic_string()
	{
		$this->assertEquals
			(
				Text::lpad(10, 'test', '*'),
				'******test'
			);
	}

	/** @test */ function
	prettybytes_works_with_basic_values()
	{
		$this->assertEquals
			(
				Text::prettybytes(1),
				'1 B'
			);

		$this->assertEquals
			(
				Text::prettybytes(1024),
				'1 KiB'
			);

		$this->assertEquals
			(
				Text::prettybytes(1048576),
				'1 MiB'
			);

		$this->assertEquals
			(
				Text::prettybytes(1073741824),
				'1 GiB'
			);
	}

	/** @test */ function
	camelcase_from_dashcase()
	{
		$this->assertEquals
			(
				Text::camelcase_from_dashcase('the-test-case'),
				'TheTestCase'
			);
	}

	/** @test */ function
	camelcase_from()
	{
		$this->assertEquals
			(
				Text::camelcase_from('the test case'),
				'TheTestCase'
			);
	}

} # test

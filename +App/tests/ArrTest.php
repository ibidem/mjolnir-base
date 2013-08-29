<?php namespace mjolnir\base\tests;

use \mjolnir\base\Arr;

class ArrTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Arr'));
	}

	/** @test */ function
	associative_from_works_with_only_array_input()
	{
		$array = array
			(
				[ 'id' => '1', 'title' => 'test1' ],
				[ 'id' => '2', 'title' => 'test2' ],
				[ 'id' => '3', 'title' => 'test3' ],
			);

		$assoc = array
			(
				'1' => 'test1',
				'2' => 'test2',
				'3' => 'test3'
			);

		$result = Arr::associative_from($array);
		$this->assertEquals($result, $assoc);
	}

	/** @test */ function
	associative_from_works_with_custom_title_and_key()
	{
		$array = array
			(
				[ 'somekey' => '1', 'atitle' => 'test1' ],
				[ 'somekey' => '2', 'atitle' => 'test2' ],
				[ 'somekey' => '3', 'atitle' => 'test3' ],
			);

		$assoc = array
			(
				'1' => 'test1',
				'2' => 'test2',
				'3' => 'test3'
			);

		$result = Arr::associative_from($array, 'somekey', 'atitle');
		$this->assertEquals($result, $assoc);
	}

	/** @test */ function
	implode_works_on_basic_array()
	{
		$array = array
			(
				'1' => 'test1',
				'2' => 'test2',
				'3' => 'test3'
			);

		$result = Arr::implode('/', $array, function ($key, $value) {
			return $value;
		});

		$this->assertEquals($result, 'test1/test2/test3');
	}

	/** @test */ function
	implode_ignores_false()
	{
		$array = array
			(
				'1' => 'test1',
				'2' => 'test2',
				'3' => 'test3'
			);

		$result = Arr::implode('/', $array, function ($key, $value) {
			if ($value != 'test2')
			{
				return $value;
			}
			else # ignored
			{
				return false;
			}
		});

		$this->assertEquals($result, 'test1/test3');
	}

	/** @test */ function
	mirror_works_with_simple_array()
	{
		$array = [ 'alice', 'bob', 'gordon' ];

		$this->assertEquals
			(
				Arr::mirror($array),
				[ 'alice' => 'alice', 'bob' => 'bob', 'gordon' => 'gordon' ]
			);
	}

	/** @test */ function
	gather_works_with_simple_array()
	{
		$array = array
			(
				[ 'somekey' => '1', 'atitle' => 'test1' ],
				[ 'somekey' => '2', 'atitle' => 'test2' ],
				[ 'somekey' => '3', 'atitle' => 'test3' ],
			);

		$this->assertEquals
			(
				Arr::gather($array, 'atitle'),
				['test1', 'test2', 'test3']
			);
	}

	/** @test */ function
	filter_works_with_simple_array()
	{
		$array = array
			(
				'1' => 'test1',
				'2' => 'test2',
				'3' => 'test3'
			);

		$result = Arr::filter($array, function ($key, $entry) {
			return $entry != 'test2';
		});

		$this->assertEquals
			(
				$result,
				['1' => 'test1', '3' => 'test3']
			);
	}

	/** @test */ function
	convert_works_with_simple_array()
	{
		$array = array
			(
				'test1',
				'test2',
				'test3'
			);

		$this->assertEquals
			(
				Arr::convert($array, function ($entry) { return 'x'.$entry.'x'; }),
				['xtest1x', 'xtest2x', 'xtest3x']
			);
	}

	/** @test */ function
	ol_works_with_simple_arrays()
	{
		$array1 = array
			(
				'test1',
				'test2',
				'test3'
			);
		$array2 = array
			(
				'test5',
				'test6',
				'test7'
			);
		$array3 = array
			(
				'test8',
				'test9',
			);

		$expected = array
			(
				'test1',
				'test2',
				'test3',
				'test5',
				'test6',
				'test7',
				'test8',
				'test9'
			);

		$this->assertEquals
			(
				Arr::ol($array1, $array2, $array3),
				$expected
			);
	}

	/** @test */ function
	index_works_with_simple_arrays()
	{
		$array1 = array
			(
				'test1',
				'test2',
				'test3',
				'test5',
			);
		$array2 = array
			(
				'test3',
				'test5',
				'test6',
				'test7'
			);
		$array3 = array
			(
				'test7',
				'test8',
				'test8',
				'test9',
			);

		$expected = array
			(
				'test1',
				'test2',
				'test3',
				'test5',
				'test6',
				'test7',
				'test8',
				'test9'
			);

		$this->assertEquals
			(
				Arr::index($array1, $array2, $array3),
				$expected
			);
	}

	/** @test */ function
	merge_works_with_simple_arrays()
	{
		$array1 = array
			(
				'apples' => 'blue',
				'pears' => 'yellow',
				'grapes' => 'teal',
			);
		$array2 = array
			(
				'apples' => 'red',
				'grapes' => 'pink',
			);
		$array3 = array
			(
				'grapes' => 'purple',
			);

		$expected = array
			(
				'apples' => 'red',
				'pears' => 'yellow',
				'grapes' => 'purple',
			);

		$this->assertEquals
			(
				Arr::merge($array1, $array2, $array3),
				$expected
			);
	}

	/** @test */ function
	call_accepts_null()
	{
		Arr::call(null);
	}

	/** @test */ function
	call_works_with_simple_arrays()
	{
		$result = [];

		$callbacks = array
			(
				function () use (&$result)
					{
						$result[] = 1;
					},
				function () use (&$result)
					{
						$result[] = 2;
					},
				function () use (&$result)
					{
						$result[] = 3;
					},
			);


		Arr::call($callbacks);

		$this->assertEquals($result, [1, 2, 3]);
	}

} # test

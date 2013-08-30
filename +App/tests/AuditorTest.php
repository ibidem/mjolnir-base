<?php namespace mjolnir\base\tests;

use \mjolnir\base\Auditor;

class AuditorTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\base\Auditor'));
	}

	/** @test */ function
	can_add_rules()
	{
		\app\Auditor::instance(['xxx' => 1])
			->rule('xxx', 'not_empty');
	}

	/** @test */ function
	basic_not_empty_test_works()
	{
		$check = \app\Auditor::instance(['xxx' => 'test'])
			->rule('xxx', 'not_empty')
			->check();

		$this->assertEquals($check, true);

		$check = \app\Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not_empty')
			->check();

		$this->assertEquals($check, false);
	}

	/** @test */ function
	basic_not_empty_test_with_custom_proof()
	{
		$check = \app\Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not_empty', true)
			->check();

		$this->assertEquals($check, true);

		$check = \app\Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not_empty', false)
			->check();

		$this->assertEquals($check, false);
	}

	/** @test */ function
	basic_not_empty_test_with_custom_callable_proof()
	{
		$check = \app\Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not_empty', function ($field, $field) { return true; })
			->check();

		$this->assertEquals($check, true);

		$check = \app\Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not_empty', function ($field, $field) { return false; })
			->check();

		$this->assertEquals($check, false);
	}

	/** @test */ function
	error_retrieval_works()
	{
		$errors = \app\Auditor::instance(['xxx' => ''])
			->adderrormessages(['xxx' => ['not_empty' => 'success']])
			->rule('xxx', 'not_empty')
			->errors();

		$this->assertEquals($errors, ['xxx' => ['success']]);

		$errors = \app\Auditor::instance(['xxx' => 'test'])
			->adderrormessages(['xxx' => ['not_empty' => 'success']])
			->rule('xxx', 'not_empty')
			->errors();

		$this->assertEquals($errors, null);
	}

} # test

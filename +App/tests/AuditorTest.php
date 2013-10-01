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
		Auditor::instance(['xxx' => 1])
			->rule('xxx', 'not-empty');
	}

	/** @test */ function
	basic_not_empty_test_works()
	{
		$check = Auditor::instance(['xxx' => 'test'])
			->rule('xxx', 'not-empty')
			->check();

		$this->assertEquals($check, true);

		$check = Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not-empty')
			->check();

		$this->assertEquals($check, false);
	}

	/** @test */ function
	basic_not_empty_test_with_custom_proof()
	{
		$check = Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not-empty', true)
			->check();

		$this->assertEquals($check, true);

		$check = Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not-empty', false)
			->check();

		$this->assertEquals($check, false);
	}

	/** @test */ function
	basic_not_empty_test_with_custom_callable_proof()
	{
		$check = Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not-empty', function ($field, $field) { return true; })
			->check();

		$this->assertEquals($check, true);

		$check = Auditor::instance(['xxx' => ''])
			->rule('xxx', 'not-empty', function ($field, $field) { return false; })
			->check();

		$this->assertEquals($check, false);
	}

	/** @test */ function
	error_retrieval_works()
	{
		$errors = Auditor::instance(['xxx' => ''])
			->adderrormessages(['xxx' => ['not-empty' => 'success']])
			->rule('xxx', 'not-empty')
			->errors();

		$this->assertEquals($errors, ['xxx' => [ 'not-empty' => 'success']]);

		$errors = Auditor::instance(['xxx' => 'test'])
			->adderrormessages(['xxx' => ['not-empty' => 'success']])
			->rule('xxx', 'not-empty')
			->errors();

		$this->assertEquals($errors, null);
	}

	/** @test */ function
	exporting_rules_works()
	{
		$rules = Auditor::instance(['xxx' => ''])
			->adderrormessages(['xxx' => ['not-empty' => 'success']])
			->rule('xxx', 'not-empty')
			->export();

		$this->assertEquals($rules, [ 'rules' => [ 'xxx' => ['not-empty' => 'success']] ]);
	}

} # test

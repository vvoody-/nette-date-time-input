<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

$container = require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\DateTimeInput;
use Achse\DateTimeInput\IDateTimeFormatter;
use Achse\DateTimeInput\SimpleDateTimeFormatter;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;



class DateTimeInputTest extends TestCase
{

	/**
	 * @var IDateTimeFormatter
	 */
	private $formatter;



	protected function setUp()
	{
		parent::setUp();

		$this->formatter = new SimpleDateTimeFormatter();
	}



	public function testSimple()
	{

		$input = new DateTimeInput('caption', $this->formatter);
		Assert::null($input->getValue());

		$testDateTime = '2013-12-11 10:09:08';
		$input->setValue(new DateTime($testDateTime));
		Assert::true(DateTimeInput::validateDateInputValid($input));
		Assert::equal(new DateTime($testDateTime), $input->getValue());

		$input->setValue('');
		Assert::true(DateTimeInput::validateDateInputValid($input));
		Assert::equal(NULL, $input->getValue());

		$input->setValue(NULL);
		Assert::true(DateTimeInput::validateDateInputValid($input));
		Assert::equal(NULL, $input->getValue());
	}

}



(new DateTimeInputTest())->run();

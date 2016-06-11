<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\DateTimeInput;
use Achse\DateTimeInput\DateTimeInputFactory;
use Achse\DateTimeInput\SimpleDateTimeConverter;
use Tester\Assert;
use Tester\TestCase;



class DateTimeInputFactoryTest extends TestCase
{

	public function testCreate()
	{
		$result = DateTimeInputFactory::create(
			'awesomeLabel',
			'Y-m-d H:i:s',
			SimpleDateTimeConverter::ALL_SYMBOLS_ALLOWED
		);

		Assert::type(DateTimeInput::class, $result);
	}



	public function testCreateWithConverter()
	{
		$result = DateTimeInputFactory::create(
			'awesomeLabel',
			new SimpleDateTimeConverter('Y-m-d H:i:s', SimpleDateTimeConverter::ALL_SYMBOLS_ALLOWED)
		);

		Assert::type(DateTimeInput::class, $result);
	}

}



(new DateTimeInputFactoryTest())->run();

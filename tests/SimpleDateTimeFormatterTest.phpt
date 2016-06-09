<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\SimpleDateTimeFormatter;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;



class SimpleDateTimeFormatterTest extends TestCase
{

	/**
	 * @dataProvider getDataForParsePatternTest
	 *
	 * @param DateTime $expected
	 * @param string $toBeParsed
	 * @param string $pattern
	 * @param bool $saveSymbols
	 */
	public function testParsePattern(
		$expected,
		$toBeParsed,
		$pattern,
		$saveSymbols = SimpleDateTimeFormatter::SAVE_SYMBOLS_ONLY
	) {
		$formatter = new SimpleDateTimeFormatter($pattern, $saveSymbols);
		Assert::equal($pattern, $formatter->getPattern());

		Assert::true($formatter->isValid($toBeParsed));
		Assert::equal($expected, $formatter->parse($toBeParsed));
	}



	/**
	 * @return array
	 */
	public function getDataForParsePatternTest()
	{
		return [
			[new DateTime('2015-05-18 00:00:00'), '18. 5. 2015', 'j. n. Y'],
			[
				new DateTime('2015-01-02 03:04:05'),
				'2015-01-02 03:04:05',
				'Y-m-d H:i:s',
				SimpleDateTimeFormatter::ALL_SYMBOLS_ALLOWED
			],
		];
	}



	/**
	 * @dataProvider getDataForIsValid
	 *
	 * @param bool $expected
	 * @param string $input
	 * @param string $pattern
	 * @param bool $saveSymbols
	 */
	public function testIsValid(
		$expected,
		$input,
		$pattern,
		$saveSymbols = SimpleDateTimeFormatter::SAVE_SYMBOLS_ONLY
	) {
		$formatter = new SimpleDateTimeFormatter($pattern, $saveSymbols);
		Assert::equal($expected, $formatter->isValid($input));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsValid()
	{
		return [
			[TRUE, '6. 10. 2015', 'j. n. Y'],
			[TRUE, '6.10.2015', 'j. n. Y'],
			[TRUE, ' 6.     10.    2015   ', 'j. n. Y'],
			[TRUE, "\t6. \t 10.\t2015 \t ", 'j. n. Y'],

			[TRUE, '18. 5. 2015', 'j. n. Y'],
			[FALSE, '1. 13. 2015', 'j. n. Y'],
			[FALSE, '99. 1. 2015', 'j. n. Y'],
			[FALSE, '32. 1. 2015', 'j. n. Y'],

			[FALSE, 'tralala', 'j. n. Y'],
			[FALSE, 'tralala', 'Y-m-d H:i:s', SimpleDateTimeFormatter::ALL_SYMBOLS_ALLOWED],
			
			[FALSE, '2015-10-24 12:13:14', 'Y-m-d', SimpleDateTimeFormatter::ALL_SYMBOLS_ALLOWED],
		];
	}

}



(new SimpleDateTimeFormatterTest())->run();

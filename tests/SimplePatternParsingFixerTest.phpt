<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\SimplePatternParsingFixer;
use Tester\Assert;
use Tester\TestCase;



class SimplePatternParsingFixerTest extends TestCase
{

	/**
	 * @dataProvider getDataForFix
	 *
	 * @param string $expected
	 * @param string $given
	 * @param string $pattern
	 */
	public function testFix($expected, $given, $pattern)
	{
		$fixer = new SimplePatternParsingFixer();
		$result = $fixer->fixCurrentTimeValues(\DateTime::createFromFormat($pattern, $given), $pattern);
		Assert::equal($expected, $result->format('Y-m-d H:i:s'));
	}



	/**
	 * @return array
	 */
	public function getDataForFix()
	{
		return [
			['0000-01-01 00:00:00', '', ''],

			['2015-10-23 00:00:00', '2015-10-23', 'Y-m-d'],
			['2015-10-23 09:10:11', '2015-10-23 09:10:11', 'Y-m-d H:i:s'],

			['0000-01-01 09:10:11', '09:10:11', 'H:i:s'],

			['2016-06-06 09:10:11', 'Mon 6. Jun 2016 09:10:11 AM', 'D j. M Y H:i:s A'],
			['2016-06-06 21:10:11', 'Mon 6. Jun 2016 09:10:11 PM', 'D j. M Y H:i:s A'],
		];
	}

}



(new SimplePatternParsingFixerTest())->run();

<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\NonSafePatternDetectedException;
use Achse\DateTimeInput\Tools;
use Tester\Assert;
use Tester\TestCase;



class ToolsTest extends TestCase
{

	/**
	 * @dataProvider getDataForIsSymbolInPattern
	 *
	 * @param bool $expected
	 * @param string $pattern
	 * @param string $symbol
	 */
	public function testIsSymbolInPattern($expected, $pattern, $symbol)
	{
		Assert::equal($expected, Tools::isSymbolInPattern($pattern, $symbol));
	}



	/**
	 * @return array
	 */
	public function getDataForIsSymbolInPattern()
	{
		return [
			[TRUE, 'Y-m-d H:i:s', 'd'],
			[FALSE, 'Y-m-d H:i:s', 'q'],

			[FALSE, 'Y-m-\\d H:i:s', 'd'],
			[TRUE, 'Y-m-\\\\d H:i:s', 'd'],
		];
	}



	/**
	 * @dataProvider getDataForCheckSaveSymbols
	 *
	 * @param bool $expectedException
	 * @param string $pattern
	 */
	public function testCheckSaveSymbols($expectedException, $pattern)
	{
		if ($expectedException) {
			Assert::exception(
				function () use ($pattern) {
					Tools::checkPattern($pattern);
				},
				NonSafePatternDetectedException::class
			);
		} else {
			Tools::checkPattern($pattern);
		}
	}



	/**
	 * @return array
	 */
	public function getDataForCheckSaveSymbols()
	{
		return [
			[TRUE, 'Y-m-d H:i:s'],
			[FALSE, 'Y-n-j H:i:s'],
		];
	}

}



(new ToolsTest())->run();

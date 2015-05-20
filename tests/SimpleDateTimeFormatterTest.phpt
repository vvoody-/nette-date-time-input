<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

$container = require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\NonSafePatternDetectedException;
use Achse\DateTimeInput\SimpleDateTimeFormatter;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;



class SimpleDateTimeFormatterTest extends TestCase
{

	public function testDefault()
	{
		$formatter = new SimpleDateTimeFormatter();
		Assert::equal('Y-m-d H:i:s', $formatter->getPattern());

		$toBeParsed = '2015-01-02 03:04:05';
		Assert::false($formatter->isValid('tralala'));
		Assert::true($formatter->isValid($toBeParsed));
		Assert::equal(new DateTime('2015-01-02 03:04:05'), $formatter->parse($toBeParsed));
	}



	public function testUseCzechPattern()
	{
		$formatter = new SimpleDateTimeFormatter('j. n. Y');
		Assert::equal('j. n. Y', $formatter->getPattern());

		$toBeParsed = '18. 5. 2015';
		Assert::true($formatter->isValid($toBeParsed));
		Assert::equal(new DateTime('2015-05-18 00:00:00'), $formatter->parse($toBeParsed)->setTime(0, 0, 0));

		Assert::false($formatter->isValid('1. 13. 2015'));
		Assert::false($formatter->isValid('99. 1. 2015'));
		Assert::false($formatter->isValid('32. 1. 2015'));
	}



	public function testSafePatternCheck()
	{
		Assert::exception(function () {
			$formatter = new SimpleDateTimeFormatter('d. m. Y');
		},
			NonSafePatternDetectedException::class
		);

		$formatter = new SimpleDateTimeFormatter('\d: j; \m: n;\y: Y');
		Assert::equal(new DateTime('2015-12-05'), $formatter->parse('d: 5; m: 12;y: 2015')->setTime(0, 0, 0));
	}

}

(new SimpleDateTimeFormatterTest())->run();

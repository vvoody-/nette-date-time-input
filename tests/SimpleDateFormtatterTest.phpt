<?php

namespace Achse\Utils\Test\Convert;

$container = require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\SimpleDateFormatter;
use Nette\Utils\DateTime;
use Tester\Assert;



$formatter = new SimpleDateFormatter();
Assert::equal(new DateTime('2015-01-02 03:04:05'), $formatter->parse('2015-01-02 03:04:05'));

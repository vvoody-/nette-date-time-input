<?php

namespace Achse\Utils\Test\Convert;

$container = require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\DateTimeInput;
use Achse\DateTimeInput\SimpleDateFormatter;
use Nette\Utils\DateTime;
use Tester\Assert;



$formatter = new SimpleDateFormatter();
$input = new DateTimeInput('caption', $formatter);
Assert::null($input->getValue());

$testDateTime = '2013-12-11 10:09:08';
$input->setValue(new DateTime($testDateTime));
Assert::equal(new DateTime($testDateTime), $input->getValue());

$input->setValue('');
Assert::equal(NULL, $input->getValue());

$input->setValue(NULL);
Assert::equal(NULL, $input->getValue());

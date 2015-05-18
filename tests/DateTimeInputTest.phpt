<?php

namespace Achse\DateTimeInput\Test;

$container = require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\DateTimeInput;
use Achse\DateTimeInput\SimpleDateTimeFormatter;
use Nette\Utils\DateTime;
use Tester\Assert;



$formatter = new SimpleDateTimeFormatter();
$input = new DateTimeInput('caption', $formatter);
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


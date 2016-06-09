[![Downloads this Month](https://img.shields.io/packagist/dm/achse/nette-date-time-input.svg)](https://packagist.org/packages/achse/nette-date-time-input)
[![Latest Stable Version](https://poser.pugx.org/achse/nette-date-time-input/v/stable)](https://github.com/achse/nette-date-time-input/releases)
[![Build Status](https://travis-ci.org/Achse/nette-date-time-input.svg?branch=master)](https://travis-ci.org/Achse/nette-date-time-input)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Achse/nette-date-time-input/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Achse/nette-date-time-input/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Achse/nette-date-time-input/badge.svg?branch=master)](https://coveralls.io/github/Achse/nette-date-time-input?branch=master)

# Installation
```
composer require achse/nette-date-time-input
```

# Usage
Basic usage is to add into your `BaseForm` or `FormElements` trait code like this:

```php
const DEFAULT_DATE_FORMAT = 'j. n. Y';

/**
 * @param string $name
 * @param string|NULL $label
 * @param IDateTimeFormatter|string $dateFormatterOrFormat
 * @return DateTimeInput
 */
public function addDate($name, $label = NULL, $dateFormatterOrFormat = BaseForm::DEFAULT_DATE_FORMAT)
{
	/** @var IDateTimeFormatter $dateFormatter */
	$dateFormatter = $dateFormatterOrFormat instanceof IDateTimeFormatter
		? $dateFormatterOrFormat
		: new SimpleDateTimeFormatter($dateFormatterOrFormat);

	return $this[$name] = new DateTimeInput($label, $dateFormatter);
```

# How does it work?
It creates simple text input. Handling client side is fully up to you. (For example: https://eonasdan.github.io/bootstrap-datetimepicker/)
 
As result it returns `DateTime` object. Internally it use:
* `IDateTimeFormatter` - responsible for conversion from string to `DateTime` object and *vice versa*.
* `IDateTimeFixer` - responsible for removing PHP specific behavior like this: 

![](https://raw.githubusercontent.com/Achse/nette-date-time-input/master/examples/createFromFormat-now.jpg)

You can provide both of them as service via constructor. If not specified, single new object is created
for each input.

## Default formatter: `SimpleDateTimeFormatter` and what "safe symbols" means?
In PHP, method `DateTime::createFromFormat` has this really unexpected behavior:

![](https://raw.githubusercontent.com/Achse/nette-date-time-input/master/examples/createFromFormat.jpg)
 
Therefore `SimpleDateTimeFormatter` prevents you from being affected by this "language feature". 

It works line this:

1. It strips all duplicate whitespaces and trims the string,
2. `DateTime::createFromFormat` creates `DateTime` object,
3. object is formatted by original patter back to string,
4. string is compared, if is same as input string.

*(For more you can see: `SimpleDateTimeFormatter::parseValue` method.)* 

But, there is a **leading zero** problem. 
* You insert: `1. 1. 2015` with pattern `d. m. Y`,
* algorithm creates object and ties to compare it with original,
* but by pattern created: `01. 01. 2015` `!==` (original) `1. 1. 2015`.

Because of this, it's strongly recommended to use only no-leading-zero formats in your datepicker.

# Contribution
* If you wrote better (alternative) `IDateTimeFormatter` send me pull request or just send me email. I'll be really happy to integrate it into package.
* If you have ANY suggestion or idea how to make it better I'll be happy for every opened issue.

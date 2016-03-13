[![Downloads this Month](https://img.shields.io/packagist/dm/achse/nette-date-time-input.svg)](https://packagist.org/packages/achse/nette-date-time-input)
[![Latest Stable Version](https://poser.pugx.org/achse/nette-date-time-input/v/stable)](https://github.com/achse/nette-date-time-input/releases)
![](https://travis-ci.org/Achse/nette-date-time-input.svg?branch=master)
![](https://scrutinizer-ci.com/g/Achse/nette-date-time-input/badges/quality-score.png?b=master)
![](https://scrutinizer-ci.com/g/Achse/nette-date-time-input/badges/coverage.png?b=master)

# Installation
```
composer require achse/nette-date-time-input
```

# Usage
Add into your `BaseForm` or `FormElements` trait.

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

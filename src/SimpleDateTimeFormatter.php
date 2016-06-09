<?php

namespace Achse\DateTimeInput;

use Nette\Utils\DateTime;
use Nette\Utils\Strings;



class SimpleDateTimeFormatter implements IDateTimeFormatter
{

	const SAVE_SYMBOLS_ONLY = TRUE;
	const ALL_SYMBOLS_ALLOWED = FALSE;

	/**
	 * @var string
	 */
	protected $pattern;

	/**
	 * @var IDateTimeFixer|NULL
	 */
	private $dateTimeFixer;



	/**
	 * @param string $pattern
	 * @param bool $saveSymbolsOnly
	 * @param IDateTimeFixer|NULL $dateTimeFixer
	 */
	public function __construct(
		$pattern,
		$saveSymbolsOnly = self::SAVE_SYMBOLS_ONLY,
		IDateTimeFixer $dateTimeFixer = NULL
	) {
		if ($saveSymbolsOnly) {
			Tools::checkPattern($pattern);
		}

		$this->pattern = $pattern;
		$this->dateTimeFixer = $dateTimeFixer !== NULL ? $dateTimeFixer : new SimplePatternParsingFixer();
	}



	/**
	 * @inheritdoc
	 */
	public function format(\DateTime $value)
	{
		return $value->format($this->pattern);
	}



	/**
	 * @inheritdoc
	 */
	public function parse($value)
	{
		if ($value === NULL || $value === '') {
			return NULL;
		}

		return $this->parseValue($value, $this->pattern);
	}



	/**
	 * @inheritdoc
	 */
	public function isValid($value)
	{
		try {
			$this->parseValue($value, $this->pattern);
		} catch (DateTimeParseException $e) {
			return FALSE;
		}

		return TRUE;
	}



	/**
	 * @return string
	 */
	public function getPattern()
	{
		return $this->pattern;
	}



	/**
	 * @param string $value
	 * @param string $pattern
	 * @return DateTime
	 * @throws DateTimeParseException
	 */
	protected function parseValue($value, $pattern)
	{
		$value = $this->strip($value);

		if (($parsed = DateTime::createFromFormat($pattern, $value)) === FALSE) {
			throw new DateTimeParseException("Value does not match desired format: '{$pattern}'.");
		}

		$error = DateTime::getLastErrors();
		if ($error['error_count'] > 0 || $error['warning_count'] > 0) {
			throw new DateTimeParseException(
				'Invalid date given. ' .
				'Errors: ' . implode(', ', $error['errors']) . ' ' .
				'Warnings: ' . implode(', ', $error['warnings'])
			);
		}

		$strippedCrossCheckValue = $this->strip($parsed->format($pattern));

		if ($value !== $strippedCrossCheckValue) {
			throw new DateTimeParseException(
				"Invalid date given. Check value does not match original. ['{$strippedCrossCheckValue}' !== '{$value}']"
			);
		}

		return $this->dateTimeFixer->fixCurrentTimeValues($parsed, $this->pattern);
	}



	/**
	 * @param string $value
	 * @return string
	 */
	protected function strip($value)
	{
		return Strings::replace($value, '#\s+#', '');
	}

}

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

		$parsed = DateTime::createFromFormat($pattern, $value);
		$errors = DateTime::getLastErrors();
		if ($parsed === FALSE || $errors['error_count'] > 0 || $errors['warning_count'] > 0) {
			$message = sprintf(
				'Invalid date given. Errors: [%s], Warnings: [%s]',
				implode(', ', $errors['errors']),
				implode(', ', $errors['warnings'])
			);
			throw new DateTimeParseException(
				sprintf("Value does not match desired format: '%s'. Error message: '%s'", $pattern, $message)
			);
		}

		$strippedCrossCheckValue = $this->strip($parsed->format($pattern));

		if ($value !== $strippedCrossCheckValue) {
			throw new DateTimeParseException(
				sprintf(
					"Invalid date given. Check value does not match original. ['%s' !== '%s']",
					$strippedCrossCheckValue,
					$value
				)
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

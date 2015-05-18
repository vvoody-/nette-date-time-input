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
	protected $pattern = 'Y-m-d H:i:s';



	/**
	 * @param string|NULL $pattern
	 * @param bool $saveSymbolsOnly
	 */
	public function __construct($pattern = NULL, $saveSymbolsOnly = self::SAVE_SYMBOLS_ONLY)
	{
		if ($pattern !== NULL) {

			if ($saveSymbolsOnly) {
				self::checkPattern($pattern);
			}

			$this->pattern = $pattern;
		}
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

		return static::validateValue($value, $this->pattern);
	}



	/**
	 * @inheritdoc
	 */
	public function isValid($value)
	{
		try {
			static::validateValue($value, $this->pattern);
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
	 * @Todo Separate this method to some DateTime Utils class
	 *
	 * @param string $value
	 * @param string $pattern
	 * @return DateTime
	 * @throws DateTimeParseException
	 */
	private static function validateValue($value, $pattern)
	{
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

		if (($checkValue = $parsed->format($pattern)) !== $value) {
			throw new DateTimeParseException(
				"Invalid date give. Check value does not match original. ['{$checkValue}' !== '{$value}']"
			);
		}

		return $parsed;
	}



	/**
	 * @param string $pattern
	 * @throws NonSafePatternDetectedException
	 */
	private static function checkPattern($pattern)
	{
		// Remove \\ for escaping the slash.
		$pattern = str_replace('\\\\', '', $pattern);

		$unsafeSymbols = [
			'd' => 'j',
			'm' => 'n',
		];

		foreach ($unsafeSymbols as $symbol => $recommended) {

			if (($position = strpos($pattern, $symbol)) !== FALSE) {

				if ($position === 0 || $pattern[$position - 1] !== '\\') {
					throw new NonSafePatternDetectedException(
						"Potentially unsafe symbol found in Pattern: '{$symbol}'. Use '{$recommended}' " .
						"if possible set SaveSymbol mode OFF."
					);

				}
			}
		}
	}

}

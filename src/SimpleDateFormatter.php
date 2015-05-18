<?php

namespace Achse\DateTimeInput;

use Nette\Utils\DateTime;



class SimpleDateFormatter implements IDateFormatter
{

	/**
	 * @var string
	 */
	protected $formatPattern = 'Y-m-d H:i:s';



	/**
	 * @param string|NULL $formatPattern
	 */
	public function __construct($formatPattern = NULL)
	{
		if ($formatPattern !== NULL) {
			$this->formatPattern = $formatPattern;
		}
	}



	/**
	 * @param \DateTime $value
	 * @return string
	 */
	public function format(\DateTime $value)
	{
		return $value->format($this->formatPattern);
	}



	/**
	 * @param string $value
	 * @return \DateTime
	 * @throws DateTimeParseException
	 */
	public function parse($value)
	{
		if ($value === NULL || $value === '') {
			return NULL;
		}

		$parsed = DateTime::createFromFormat($this->formatPattern, $value);

		if ($parsed === FALSE) {
			throw new DateTimeParseException("Value");
		}

		return $parsed;
	}
}

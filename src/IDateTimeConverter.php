<?php

namespace Achse\DateTimeInput;


interface IDateTimeConverter
{

	/**
	 * @param \DateTime $value
	 * @return string
	 */
	public function format(\DateTime $value);



	/**
	 * @param string $value
	 * @return \DateTime|NULL
	 * @throws DateTimeParseException
	 */
	public function parse($value);



	/**
	 * @param string $value
	 * @return bool
	 */
	public function isValid($value);



	/**
	 * @return string
	 */
	public function getPattern();

}

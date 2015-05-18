<?php

namespace Achse\DateTimeInput;


interface IDateFormatter
{

	/**
	 * @param \DateTime $value
	 * @return string
	 */
	public function format(\DateTime $value);



	/**
	 * @param string $value
	 * @return \DateTime|NULL
	 */
	public function parse($value);
}

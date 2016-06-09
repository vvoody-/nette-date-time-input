<?php

namespace Achse\DateTimeInput;



interface IDateTimeFixer
{

	/**
	 * @param \DateTime $dateTime
	 * @param string $pattern
	 * @return \DateTime
	 */
	public function fixCurrentTimeValues(\DateTime $dateTime, $pattern);
	
}

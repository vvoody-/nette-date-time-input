<?php

namespace Achse\DateTimeInput;

use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;



class DateTimeInput extends TextInput
{

	/**
	 * @var IDateFormatter
	 */
	protected $dateFormatter;



	/**
	 * @param string $caption
	 * @param IDateFormatter $dateFormatter
	 */
	public function __construct($caption, IDateFormatter $dateFormatter)
	{
		parent::__construct($caption);

		$this->dateFormatter = $dateFormatter;
	}



	/**
	 * @return Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		$control->addClass('datetimepicker');

		return $control;
	}



	/**
	 * @param \DateTime|string $value
	 * @return static
	 */
	public function setValue($value)
	{
		if ($value instanceof \DateTime) {
			$value = $this->dateFormatter->format($value);
		}

		return parent::setValue($value);
	}



	/**
	 * @return \DateTime|NULL
	 */
	public function getValue()
	{
		return $this->dateFormatter->parse(parent::getValue());
	}

}

<?php

namespace Achse\DateTimeInput;

use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;



class DateTimeInput extends TextInput
{

	/**
	 * @var IDateTimeFormatter
	 */
	protected $dateFormatter;

	/**
	 * @var string|NULL
	 */
	protected $rawValue = NULL;



	/**
	 * @param string|NULL $caption
	 * @param IDateTimeFormatter $dateFormatter
	 */
	public function __construct($caption, IDateTimeFormatter $dateFormatter)
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

		$this->rawValue = trim($value);

		return parent::setValue($value);
	}



	/**
	 * @return \DateTime|NULL
	 */
	public function getValue()
	{
		return $this->dateFormatter->parse(parent::getValue());
	}



	/**
	 * @inheritdoc
	 */
	public function addRule($operation, $message = NULL, $arg = NULL)
	{
		if ($operation === Form::FILLED) {
			$operation = __CLASS__ . '::validateDateInputFilled';

		} elseif ($operation === Form::VALID) {
			$operation = __CLASS__ . '::validateDateInputValid';
		}

		return parent::addRule($operation, $message, $arg);
	}



	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->rawValue === NULL || $this->rawValue === '';
	}



	/**
	 * @return bool
	 */
	public function isFilled()
	{
		return !$this->isEmpty();
	}



	/**
	 * @return bool
	 */
	public function isValid()
	{
		return $this->dateFormatter->isValid($this->rawValue);
	}



	/**
	 * @param IControl $control
	 * @return bool
	 */
	public static function validateDateInputValid(IControl $control)
	{
		if (!$control instanceof static) {
			throw new InvalidArgumentException("Given control object must be instance of '" . get_class() . "'.");
		}

		return $control->isEmpty() || $control->isValid();
	}



	/**
	 * @param IControl $control
	 * @return bool
	 */
	public static function validateDateInputFilled(IControl $control)
	{
		if (!$control instanceof static) {
			throw new InvalidArgumentException("Given control object must be instance of '" . get_class() . "'.");
		}

		return !$control->isEmpty();
	}

}

<?php

namespace Achse\DateTimeInput;

use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;



class DateTimeInput extends TextInput
{

	const HTML_CLASS = 'dateTimePicker';

	/**
	 * @var IDateTimeConverter
	 */
	protected $dateConverter;

	/**
	 * @var string|NULL
	 */
	protected $rawValue = NULL;



	/**
	 * @param string|NULL $caption
	 * @param IDateTimeConverter $dateConverter
	 */
	public function __construct($caption, IDateTimeConverter $dateConverter)
	{
		parent::__construct($caption);

		$this->dateConverter = $dateConverter;
	}



	/**
	 * @return Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		$control->addClass(self::HTML_CLASS);

		return $control;
	}



	/**
	 * @param \DateTime|string $value
	 * @return static
	 */
	public function setValue($value)
	{
		if ($value instanceof \DateTime) {
			$value = $this->dateConverter->format($value);
		}

		$this->rawValue = trim($value);

		return parent::setValue($value);
	}



	/**
	 * @return \DateTime|NULL
	 */
	public function getValue()
	{
		return $this->dateConverter->parse(parent::getValue());
	}



	/**
	 * @inheritdoc
	 */
	public function addRule($operation, $message = NULL, $arg = NULL)
	{
		if ($operation === Form::FILLED) {
			$operation = __CLASS__ . '::validateDateTimeInputFilled';

		} elseif ($operation === Form::VALID) {
			$operation = __CLASS__ . '::validateDateTimeInputValid';
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
		return $this->dateConverter->isValid($this->rawValue);
	}



	/**
	 * @inheritdoc
	 */
	public function setDefaultValue($value)
	{
		if (!$value instanceof \DateTime) {
			$type = gettype($value);

			throw new InvalidArgumentException(
				sprintf(
					"As default value, %s object must be given, '%s' given instead.",
					\DateTime::class,
					$type === 'object' ? get_class($value) : $type
				)
			);
		}

		return parent::setDefaultValue($value);
	}



	/**
	 * @param IControl $control
	 * @return bool
	 */
	public static function validateDateTimeInputValid(IControl $control)
	{
		/** @var static $control */
		self::validateControlType($control);

		return $control->isEmpty() || $control->isValid();
	}



	/**
	 * @param IControl $control
	 * @return bool
	 */
	public static function validateDateTimeInputFilled(IControl $control)
	{
		/** @var static $control */
		self::validateControlType($control);

		return !$control->isEmpty();
	}



	/**
	 * @param IControl $control
	 */
	private static function validateControlType(IControl $control)
	{
		if (!$control instanceof static) {
			throw new InvalidArgumentException(
				"Given control object must be instance of '" . get_class() . "', but '" . get_class(
					$control
				) . "' given."
			);
		}
	}

}

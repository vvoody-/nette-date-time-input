<?php

/**
 * @testCase
 */

namespace Achse\DateTimeInput\Test;

require __DIR__ . '/bootstrap.php';

use Achse\DateTimeInput\DateTimeInput;
use Achse\DateTimeInput\IDateTimeConverter;
use Achse\DateTimeInput\SimpleDateTimeConverter;
use Closure;
use Mockery;
use Mockery\MockInterface;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\InvalidArgumentException;
use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;



class DateTimeInputTest extends TestCase
{

	const HAS_ERRORS = TRUE;
	const NO_ERRORS = FALSE;

	/**
	 * @var IDateTimeConverter
	 */
	private $formatter;



	protected function setUp()
	{
		parent::setUp();

		$this->formatter = new SimpleDateTimeConverter('Y-m-d H:i:s', SimpleDateTimeConverter::ALL_SYMBOLS_ALLOWED);
	}



	public function testSimple()
	{

		$input = new DateTimeInput('caption', $this->formatter);
		Assert::null($input->getValue());

		$testDateTime = '2013-12-11 10:09:08';
		$input->setValue(new DateTime($testDateTime));
		Assert::true(DateTimeInput::validateDateTimeInputValid($input));
		Assert::equal(new DateTime($testDateTime), $input->getValue());

		$input->setValue('');
		Assert::true(DateTimeInput::validateDateTimeInputValid($input));
		Assert::equal(NULL, $input->getValue());

		$input->setValue(NULL);
		Assert::true(DateTimeInput::validateDateTimeInputValid($input));
		Assert::equal(NULL, $input->getValue());
	}



	public function testSetDefaultValue()
	{
		$input = new DateTimeInput('caption', $this->formatter);

		Assert::exception(
			function () use ($input) {
				$input->setDefaultValue('Invalid Type');
			},
			InvalidArgumentException::class,
			"As default value, DateTime object must be given, 'string' given instead."
		);

		$dateTime = new DateTime('2015-05-20 15:16:17');
		$input->setDefaultValue($dateTime);

		Assert::notSame($dateTime, $input->getValue());
		Assert::equal($dateTime, $input->getValue());
	}



	public function testGetControl()
	{
		$form = new Form();
		$input = new DateTimeInput('caption', $this->formatter);
		$input->setParent($form, 'dateTimeInput');

		Assert::equal(
			'<input type="text" name="dateTimeInput" id="frm-dateTimeInput" class="dateTimePicker">',
			$input->getControl()->render()
		);
	}



	/**
	 * @dataProvider getDataForInvalidControlTest
	 *
	 * @param string $expectedMessage
	 * @param Closure $throwingCallback
	 */
	public function testValidateControl($expectedMessage, $throwingCallback)
	{
		Assert::exception($throwingCallback, InvalidArgumentException::class, $expectedMessage);
	}



	/**
	 * @return string[][]|Closure[][]
	 */
	public function getDataForInvalidControlTest()
	{
		$expectedMessage = sprintf(
			'Given control object must be instance of \'%s\', but \'%s\' given.',
			DateTimeInput::class,
			'Mockery_0_Nette_Forms_IControl'
		);

		/** @var IControl|MockInterface $control */
		$control = Mockery::mock(IControl::class);

		return [
			[
				$expectedMessage,
				function () use ($control) {
					DateTimeInput::validateDateTimeInputFilled($control);
				}
			],
			[
				$expectedMessage,
				function () use ($control) {
					DateTimeInput::validateDateTimeInputValid($control);
				}
			],
		];
	}



	public function testIsEmptyIsFilled()
	{
		$input = new DateTimeInput('caption', $this->formatter);
		Assert::true($input->isEmpty());
		Assert::false($input->isFilled());

		$input->setDefaultValue(new \DateTime('2015-09-10 11:12:13'));
		Assert::false($input->isEmpty());
		Assert::true($input->isFilled());

		$input = $this->getInputWithMockedUserInput('definitely invalid input');
		$input->loadHttpData();
		Assert::false($input->isEmpty());
		Assert::true($input->isFilled());
	}



	/**
	 * @dataProvider getDataForValidationIsValid
	 *
	 * @param bool $expected
	 * @param string $rawInput
	 */
	public function testValidationIsValid($expected, $rawInput)
	{
		$input = $this->getInputWithMockedUserInput($rawInput);
		$input->addRule(Form::VALID, 'Not valid dude!');
		$input->loadHttpData();
		$input->validate();
		Assert::equal(
			$expected,
			$input->hasErrors(),
			'There are errors in none expected. Or no errors when expected'
		);
	}



	/**
	 * @return array
	 */
	public function getDataForValidationIsValid()
	{
		return [
			[self::NO_ERRORS, '2013-12-11 10:09:08'],

			[self::HAS_ERRORS, '2014-12-15 18:19-50'],
			[self::HAS_ERRORS, '2013-12-55 10:09:08'],

			[self::HAS_ERRORS, 'definitely not valid input'],
			[self::NO_ERRORS, NULL],
			[self::NO_ERRORS, ''],
			[self::HAS_ERRORS, 42],
		];
	}



	/**
	 * @dataProvider getDataForValidationIsFilled
	 *
	 * @param bool $expected
	 * @param string $rawInput
	 */
	public function testValidationIsFilled($expected, $rawInput)
	{
		$input = $this->getInputWithMockedUserInput($rawInput);
		$input->addRule(Form::FILLED, 'Not valid dude!');
		$input->loadHttpData();
		$input->validate();
		Assert::equal(
			$expected,
			$input->hasErrors(),
			'There are errors in none expected. Or no errors when expected'
		);
	}



	/**
	 * @return array
	 */
	public function getDataForValidationIsFilled()
	{
		return [
			[self::NO_ERRORS, '2013-12-11 10:09:08'],

			[self::NO_ERRORS, '2014-12-15 18:19-50'],
			[self::NO_ERRORS, '2013-12-55 10:09:08'],

			[self::NO_ERRORS, 'definitely not valid input'],
			[self::HAS_ERRORS, NULL],
			[self::HAS_ERRORS, ''],
			[self::NO_ERRORS, 42],
		];
	}



	/**
	 * @param string $rawValue
	 * @param IDateTimeConverter $formatter
	 * @return DateTimeInput
	 */
	private function getInputWithMockedUserInput($rawValue, IDateTimeConverter $formatter = NULL)
	{
		/** @var Form|MockInterface $form */
		$form = Mockery::mock(new Form());
		$form->shouldReceive('getHttpData')->andReturn($rawValue);

		$input = new DateTimeInput('caption', $formatter !== NULL ? $formatter : $this->formatter);
		$input->setParent($form, 'dateTime');

		return $input;
	}

}



(new DateTimeInputTest())->run();

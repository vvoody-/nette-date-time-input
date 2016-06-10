<?php

namespace Achse\DateTimeInput;

use Achse\DateTimeFormatTools\Tools;
use Nette\Object;



class SimplePatternParsingFixer extends Object implements IDateTimeFixer
{

	/**
	 * @var array
	 */
	protected $configuration = [
		'years' => [
			'symbols' => ['Y'],
		],
		'months' => [
			'symbols' => ['F', 'm', 'M', 'n'],
		],
		'days' => [
			'symbols' => ['d', 'D', 'j', 'l', 'L']
		],
		'hours' => [
			'symbols' => ['g', 'G', 'h', 'H'],
		],
		'minutes' => [
			'symbols' => ['i'],
		],
		'seconds' => [
			'symbols' => ['s'],
		],
	];

	/**
	 * In memory cache of pre-parsed patterns.
	 *
	 * @var bool[][]
	 */
	protected $symbolTypePresence = [];



	/**
	 * @inheritdoc
	 */
	public function fixCurrentTimeValues(\DateTime $dateTime, $pattern)
	{
		$symbolTypePresence = $this->resolveTimePatternPartPresence($pattern);

		$years = $this->fixValueForSymbol('years', $dateTime, $symbolTypePresence, 'Y');
		$months = $this->fixValueForSymbol('months', $dateTime, $symbolTypePresence, 'm', 1); // Month zero is not valid
		$days = $this->fixValueForSymbol('days', $dateTime, $symbolTypePresence, 'd', 1); // Day zero is not valid
		$hours = $this->fixValueForSymbol('hours', $dateTime, $symbolTypePresence, 'H');
		$minutes = $this->fixValueForSymbol('minutes', $dateTime, $symbolTypePresence, 'i');
		$seconds = $this->fixValueForSymbol('seconds', $dateTime, $symbolTypePresence, 's');

		$dateTime->setTime($hours, $minutes, $seconds);
		$dateTime->setDate($years, $months, $days);

		return $dateTime;
	}



	/**
	 * @param string $pattern
	 * @return bool[]
	 */
	protected function resolveTimePatternPartPresence($pattern)
	{
		if (!isset($this->symbolTypePresence[$pattern])) {
			$this->symbolTypePresence[$pattern] = [];
			foreach ($this->configuration as $symbolName => $symbolConfiguration) {
				$this->symbolTypePresence[$pattern][$symbolName] = Tools::isAnyOfSymbolsInPattern(
					$symbolConfiguration['symbols'],
					$pattern
				);
			}
		}

		return $this->symbolTypePresence[$pattern];
	}



	/**
	 * @param $symbolName
	 * @param \DateTime $dateTime
	 * @param $symbolTypePresence
	 * @param $symbol
	 * @param int $defaultValue
	 * @return int
	 */
	protected function fixValueForSymbol(
		$symbolName,
		\DateTime $dateTime,
		$symbolTypePresence,
		$symbol,
		$defaultValue = 0
	) {
		return $symbolTypePresence[$symbolName] ? (int) $dateTime->format($symbol) : $defaultValue;
	}

}

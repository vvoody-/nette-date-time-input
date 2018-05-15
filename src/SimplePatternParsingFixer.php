<?php

namespace Achse\DateTimeInput;

use Achse\DateTimeFormatTools\Tools;



class SimplePatternParsingFixer implements IDateTimeFixer
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

		$years = $this->fixValueForSymbol($symbolTypePresence, 'years', $dateTime, 'Y');
		$months = $this->fixValueForSymbol($symbolTypePresence, 'months', $dateTime, 'm', 1); // Month zero is not valid
		$days = $this->fixValueForSymbol($symbolTypePresence, 'days', $dateTime, 'd', 1); // Day zero is not valid
		$hours = $this->fixValueForSymbol($symbolTypePresence, 'hours', $dateTime, 'H');
		$minutes = $this->fixValueForSymbol($symbolTypePresence, 'minutes', $dateTime, 'i');
		$seconds = $this->fixValueForSymbol($symbolTypePresence, 'seconds', $dateTime, 's');

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
	 * @param bool[] $symbolTypePresence
	 * @param string $symbolName
	 * @param \DateTime $dateTime
	 * @param string $symbol
	 * @param int $defaultValue
	 * @return int
	 */
	protected function fixValueForSymbol(
		array $symbolTypePresence,
		$symbolName,
		\DateTime $dateTime,
		$symbol,
		$defaultValue = 0
	) {
		return $symbolTypePresence[$symbolName] ? (int) $dateTime->format($symbol) : $defaultValue;
	}

}

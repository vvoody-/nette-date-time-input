<?php

namespace Achse\DateTimeInput;

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

		$years = $symbolTypePresence['years'] ? (int) $dateTime->format('Y') : 0;
		$months = $symbolTypePresence['months'] ? (int) $dateTime->format('m') : 1; // Month zero is not valid
		$days = $symbolTypePresence['days'] ? (int) $dateTime->format('d') : 1; // Day zero is not valid
		$hours = $symbolTypePresence['hours'] ? (int) $dateTime->format('H') : 0;
		$minutes = $symbolTypePresence['minutes'] ? (int) $dateTime->format('i') : 0;
		$seconds = $symbolTypePresence['seconds'] ? (int) $dateTime->format('s') : 0;

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
				$this->symbolTypePresence[$pattern][$symbolName] = Tools::areSymbolsInPattern(
					$symbolConfiguration['symbols'],
					$pattern
				);
			}
		}

		return $this->symbolTypePresence[$pattern];
	}

}

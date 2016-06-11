<?php

namespace Achse\DateTimeInput;

use Nette\Object;



class DateTimeInputFactory extends Object
{

	/**
	 * @param string|NULL $label
	 * @param IDateTimeConverter|string $dateConverterOrFormat
	 * @param bool $saveSymbolsOnly
	 * @return DateTimeInput
	 */
	public static function create(
		$label,
		$dateConverterOrFormat,
		$saveSymbolsOnly = SimpleDateTimeConverter::SAVE_SYMBOLS_ONLY
	) {
		/** @var IDateTimeConverter $dateConverter */
		$dateConverter = $dateConverterOrFormat instanceof IDateTimeConverter
			? $dateConverterOrFormat
			: new SimpleDateTimeConverter($dateConverterOrFormat, $saveSymbolsOnly);

		return new DateTimeInput($label, $dateConverter);
	}

}

<?php

namespace Achse\DateTimeInput;

use Nette\Object;



class Tools extends Object
{

	const UNSAFE_SYMBOLS = [
		'd' => 'j',
		'm' => 'n',
	];



	/**
	 * @param string[] $symbols
	 * @param string $pattern
	 * @return bool
	 */
	public static function areSymbolsInPattern(array $symbols, $pattern)
	{
		foreach ($symbols as $symbol) {
			if (static::isSymbolInPattern($pattern, $symbol)) {
				return TRUE;
			}
		}

		return FALSE;
	}



	/**
	 * @param string $pattern
	 * @param string $symbol
	 * @return bool
	 */
	public static function isSymbolInPattern($pattern, $symbol)
	{
		$pattern = Tools::removeBackSlashedFromPattern($pattern);
		$position = strpos($pattern, $symbol);

		return $position !== FALSE && ($position === 0 || $pattern[$position - 1] !== '\\');
	}



	/**
	 * @param string $pattern
	 * @throws NonSafePatternDetectedException
	 */
	public static function checkPattern($pattern)
	{
		foreach (self::UNSAFE_SYMBOLS as $symbol => $recommended) {
			if (static::isSymbolInPattern($pattern, $symbol)) {
				throw new NonSafePatternDetectedException(
					"Potentially unsafe symbol found in Pattern: '{$symbol}'. Use '{$recommended}' "
					. "if possible set SaveSymbol mode OFF."
				);
			}
		}
	}



	/**
	 * @param string $pattern
	 * @return string
	 */
	protected static function removeBackSlashedFromPattern($pattern)
	{
		return str_replace('\\\\', '', $pattern);
	}

}

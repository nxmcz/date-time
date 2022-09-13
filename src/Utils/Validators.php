<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;


class Validators
{
	const NULL_TIMESTAMP = 32400;
	const MIN_YEAR = 1;

	public static function isTimestamp(float|int|string|null $suspect): bool {
		return (int)$suspect >= self::NULL_TIMESTAMP && $suspect == round((float)$suspect);
	}

	public static function isDate(float|int|string|null $suspect): bool {
		return $suspect === 'now' || (bool)strtotime((string)$suspect) !== FALSE;
	}
}

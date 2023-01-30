<?php

declare(strict_types=1);

namespace Noxem\DateTime;

use DateInterval;
use Noxem\DateTime\LocalDate\CustomHour;
use Noxem\DateTime\LocalDate\Day;
use Noxem\DateTime\LocalDate\Month;
use Noxem\DateTime\LocalDate\Week;
use Noxem\DateTime\Utils\Formatter;

enum Period: string
{
	case HOUR = 'hour';
	case DAY = 'day';
	case WEEK = 'week';
	case MONTH = 'month';
	case YEAR = 'year';
	case SHIFT = 'shift';

	/**
	 * @return array<string>
	 */
	public static function toSelectBox(): array
	{
		$a = array_map(fn ($v) => $v->value, self::cases());
		return array_combine($a, $a);
	}

	/**
	 * @return array<string>
	 */
	public static function toPeriodical(): array
	{
		$a = [];

		foreach ([self::HOUR, self::DAY, self::WEEK, self::MONTH] as $case) {
			$a[$case->value] = $case->value;
		}

		return $a;
	}

	public function getClassInstance(): string
	{
		return match ($this) {
			self::DAY, self::SHIFT => Day::class,
			self::MONTH => Month::class,
			self::WEEK => Week::class,
            self::HOUR => CustomHour::class,
			default => throw new \InvalidArgumentException("Unsupported case for generator")
		};
	}

	public function getInterval(): DateInterval
	{
		return DateInterval::createFromDateString(
			match ($this) {
				self::DAY, self::SHIFT => '1 day',
				self::MONTH => 'first day of next month',
				self::HOUR => '1 hour',
				self::WEEK => 'monday next week',
				self::YEAR => 'first day of next year',
			}
		);
	}

	public function getFormat(): string
	{
		return match ($this) {
			self::DAY, self::SHIFT => Formatter::DATE,
			self::MONTH => 'Y-m',
			self::HOUR => Formatter::DATETIME,
			self::WEEK => "Y-\WW",
			self::YEAR => 'Y',
		};
	}

	public static function toRouter(): string
	{
		return substr(implode('|', array_map(fn ($v) => $v->value, self::cases())), 0);
	}
}

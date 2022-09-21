<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use DateTimeInterface;
use Nette\Utils\Strings;
use Noxem\DateTime\DT;


class Parser
{
	public const WEEK_PATTERN = '/\d{4}-W\d{2}$/';
	public const MONTH_PATTERN = '/\d{4}-\d{2}$/';
	public const DAY_PATTERN = '/\d{4}-\d{2}-\d{2}$/';
	public const MIN_YEAR = -999999;

	public static function fromWeek(string|null|DateTimeInterface $value): ?DT
	{
		if($value instanceof DateTimeInterface) {
			return DT::getOrCreateInstance($value);
		}

		if($value !== NULL && Strings::match($value, self::WEEK_PATTERN) !== NULL) {

			$year = (int)Strings::substring($value,0, 4);
			$week = (int)Strings::substring($value,6, 2);

			// https://stackoverflow.com/a/3319413
			$dt = (int)DT::createFromParts($year, 12, 28)->format("W");

			if ($year >= self::MIN_YEAR && $week > 0 && $week <= $dt) {
				return DT::create()
					->setISODate($year, $week)
					->setTime(0,0);
			}
		}

		return NULL;
	}

	public static function fromMonth(string|null|DateTimeInterface $value): ?DT
	{
		if($value instanceof DateTimeInterface) {
			return DT::getOrCreateInstance($value);
		}

		if($value !== NULL && Strings::match($value, self::MONTH_PATTERN) !== NULL) {

			$year = (int)Strings::substring($value,0, 4);
			$month = (int)Strings::substring($value,5, 2);

			if ($year >= self::MIN_YEAR && $month > 0 && $month <= 12) {
				return DT::create()
					->setDate($year, $month, 1)
					->setTime(0,0);
			}
		}

		return NULL;
	}

	public static function fromDay(string|null|DateTimeInterface $value): ?DT
	{
		if($value instanceof DateTimeInterface) {
			return DT::getOrCreateInstance($value);
		}

		if($value !== NULL && Strings::match($value, self::DAY_PATTERN) !== NULL) {

			$year = (int)Strings::substring($value,0, 4);
			$month = (int)Strings::substring($value,5, 2);
			$day = (int)Strings::substring($value,8, 2);

			if ($year >= self::MIN_YEAR && checkdate($month, $day, $year)) {
				return DT::create()
					->setDate($year, $month, $day)
					->setTime(0,0);
			}
		}

		return NULL;
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use DateTimeInterface;
use Nette\Utils\Strings;
use Noxem\DateTime\DT;


class Parser
{
	public const HTML_WEEK_FORMAT = '%04d-W%02d';
	public const HTML_MONTH_FORMAT = '%04d-%02d';
	public const HTML_DAY_FORMAT = '%04d-%02d-%02d';

	public static function toWeek(string|null|DateTimeInterface $value): ?DT
	{
		if($value instanceof DateTimeInterface) {
			return DT::getOrCreateInstance($value);
		}

		if($value !== NULL && Strings::match($value, '/\d{4}-W\d{2}$/') !== NULL) {

			$year = (int)Strings::substring($value,0, 4);
			$week = (int)Strings::substring($value,6, 2);

			// https://stackoverflow.com/a/3319413
			$dt = (int)DT::createFromParts($year, 12, 28)->format("W");

			if ($year >= Validators::MIN_YEAR && $week > 0 && $week <= $dt) {
				return DT::create()
					->setISODate($year, $week)
					->setTime(0,0);
			}
		}

		return NULL;
	}

	public static function toMonth(string|null|DateTimeInterface $value): ?DT
	{
		if($value instanceof DateTimeInterface) {
			return DT::getOrCreateInstance($value);
		}

		if($value !== NULL && Strings::match($value, '/\d{4}-\d{2}$/') !== NULL) {

			$year = (int)Strings::substring($value,0, 4);
			$month = (int)Strings::substring($value,5, 2);

			if ($year >= Validators::MIN_YEAR && $month > 0 && $month <= 12) {
				return DT::create()
					->setDate($year, $month, 1)
					->setTime(0,0);
			}
		}

		return NULL;
	}

	public static function toDay(string|null|DateTimeInterface $value): ?DT
	{
		if($value instanceof DateTimeInterface) {
			return DT::getOrCreateInstance($value);
		}

		if($value !== NULL && Strings::match($value, '/\d{4}-\d{2}-\d{2}$/') !== NULL) {

			$year = (int)Strings::substring($value,0, 4);
			$month = (int)Strings::substring($value,5, 2);
			$day = (int)Strings::substring($value,8, 2);

			if ($year >= Validators::MIN_YEAR && checkdate($month, $day, $year)) {
				return DT::create()
					->setDate($year, $month, $day)
					->setTime(0,0);
			}
		}

		return NULL;
	}

	/*public static function convertInput(string|null|DateTimeInterface $value, string $type): ?string
	{
		$result = self::$type($value) ?? DT::create();

		return match($type) {
			'toWeek' => sprintf(self::HTML_WEEK_FORMAT, $result->year(), $result->week()),
			'toMonth' => sprintf(self::HTML_MONTH_FORMAT, $result->year(), $result->month()),
			'toDay' => sprintf(self::HTML_DAY_FORMAT, $result->year(), $result->month(), $result->day())
		};
	}*/
}

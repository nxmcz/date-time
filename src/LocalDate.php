<?php

declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeZone;
use Noxem\DateTime\Attributes\Timezoned;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\LocalDate\Day;
use Noxem\DateTime\LocalDate\Month;
use Noxem\DateTime\LocalDate\Week;

class LocalDate
{
	use Timezoned;

	public const DateSeparator = '-';

	private DT $dt;

	public function __construct(
		private int $year,
		int $month,
		int $day,
		private ?DateTimeZone $dateTimeZone = null
	) {
		$this->dt = DT::createFromParts($year, $month, $day)
			->setTimezone($this->dateTimeZone)
			->setTime(0, 0);
	}

	public function __toString(): string
	{
		return $this->format('Y-m-d');
	}

	public static function create(string|int|\DateTimeInterface|LocalDate $suspect = 'now', string $timezone = null): self
	{
		try {
			if ($suspect instanceof self) {
				$dt = $suspect;
				$day = $dt->getDay()->getNumber();
				$month = $dt->getMonth()->getNumber();
			} else {
				$dateTimeZone = new \DateTimeZone($timezone ?? date_default_timezone_get());
				$dt = DT::getOrCreateInstance($suspect)->setTimezone($dateTimeZone)->setTime(0, 0);
				$day = $dt->getDay();
				$month = $dt->getMonth();
			}

			$year = $dt->getYear();

			$timezone = $dt->getTimezone();

			return new self(
				$year,
				$month,
				$day,
				$timezone
			);
		} catch (\Exception) {
			throw BadFormatException::create()
				->withMessage('Error DT format.');
		}
	}

	public static function createFromParts(
		int $year,
		int $month,
		int $day = 1,
	): self {
		if (!checkdate($month, $day, $year)) {
			throw BadFormatException::create()
				->withMessage('Time parts are invalid');
		}

		return new self($year, $month, $day);
	}

	public static function createFromString(string $value): self
	{
		$exp = explode(self::DateSeparator, $value);
		if (count($exp) === 3) {
			$year = (int) $exp[0];
			$month = (int) $exp[1];
			$day = (int) $exp[2];

			return self::createFromParts($year, $month, $day);
		}

		throw BadFormatException::create()
			->withMessage("Time parts are invalid: $value");
	}

	public static function createFromFirst(int $year, int $month): self
	{
		return self::createFromParts($year, $month, 1);
	}

	public static function createFromLast(int $year, int $month): self
	{
		return self::createFromParts($year, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year));
	}

	public function getYear(): int
	{
		return $this->year;
	}

	public function getMonth(): Month
	{
		return new Month($this->getDT());
	}

	public function getWeek(): Week
	{
		return new Week($this->getDT());
	}

	public function getDay(): Day
	{
		return new Day($this->getDT());
	}

	public function getDayOfWeek(): int
	{
		return $this->dt->getDayOfWeek();
	}

	public function areEquals(LocalDate $equalsTo): bool
	{
		return (string) $this === (string) $equalsTo;
	}

	public function getDT(): DT
	{
		return $this->dt;
	}

	public function isWeekend(): bool
	{
		return $this->getDay()->getDayOfWeek() > 5;
	}

	public function format(string $slug): string
	{
		return $this->dt->format($slug);
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeZone;
use Noxem\DateTime\Attributes\Timezoned;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils\Formatter;

class LocalDate
{
	use Timezoned;

	public const DateSeparator = "-";
	private DT $dt;

	public function __construct(
		private int $year,
		private int $month,
		private int $day,
		private ?DateTimeZone $dateTimeZone = NULL
	) {
		$this->dt = DT::createFromParts($year, $month, $day)
			->setTimezone($this->dateTimeZone)
			->setTime(0,0);
	}

	public static function create(string|int|\DateTimeInterface|LocalDate $suspect = 'now', string $timezone = NULL): self
	{
		try {
			if($suspect instanceof self) {
				$dt = $suspect;
			} else {
				$dateTimeZone = new \DateTimeZone($timezone ?? date_default_timezone_get());
				$dt = DT::getOrCreateInstance($suspect)->setTimezone($dateTimeZone)->setTime(0,0);
			}

			return new self(
				$dt->getYear(),
				$dt->getMonth(),
				$dt->getDay(),
				$dt->getTimezone()
			);
		} catch (\Exception) {
			throw BadFormatException::create()
				->withMessage("Error DT format.");
		}
	}

	public static function createFromParts(
		int $year,
		int $month,
		int $day = 1,
	): self {

		if (!checkdate($month, $day, $year)) {
			throw BadFormatException::create()
				->withMessage("Time parts are invalid");
		}

		return new self($year, $month, $day);
	}

	public static function createFromString(string $value): self
	{
		$exp = explode(self::DateSeparator, $value);
		if( count($exp) === 3) {
			$year = (int)$exp[0];
			$month = (int)$exp[1];
			$day = (int)$exp[2];

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

	public function getMonth(): int
	{
		return $this->month;
	}

	public function getDay(): int
	{
		return $this->day;
	}

	public function getDayOfWeek(): int
	{
		return $this->dt->getDayOfWeek();
	}

	public function getDayName(): string
	{
		return strtolower($this->dt->format(Formatter::DAY_NAME));
	}

	public function areEquals(LocalDate $equalsTo): bool
	{
		return (string)$this === (string)$equalsTo;
	}

	public function getDT(): DT
	{
		return $this->dt;
	}

	public function __toString(): string {
		return "{$this->getYear()}-{$this->getMonth()}-{$this->getDay()}";
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeZone;
use Nette\Utils\Strings;
use Noxem\DateTime\Exception\BadFormatException;

class T
{
	private static int $hours;
	private static int $minutes;
	private static int $seconds;
	private static int $millis;
	private static ?DateTimeZone $dateTimeZone;

	public function __construct(
		int $hours,
		int $minutes = 0,
		int $seconds = 0,
		int $millis = 0,
		?DateTimeZone $dateTimeZone = NULL
	) {
		self::$hours = $hours;
		self::$minutes = $minutes;
		self::$seconds = $seconds;
		self::$millis = $millis;
		self::$dateTimeZone = $dateTimeZone;
	}

	public static function create(string|int|\DateTimeInterface $suspect = 'now', string $timezone = NULL): self
	{
		try {
			$dateTimeZone = new \DateTimeZone($timezone ?? date_default_timezone_get());
			$dt = DT::getOrCreateInstance($suspect)->setTimezone($dateTimeZone);

			return new self(
				$dt->getHour(),
				$dt->getMinute(),
				$dt->getSecond(),
				$dt->getMillisPart(),
				$dateTimeZone
			);
		} catch (\Exception) {
			throw BadFormatException::create()
				->withMessage("Error DT format.");
		}
	}

	public static function createFromParts(
		int $hour = 0,
		int $minute = 0,
		int $second = 0,
		int $millis = 0
	): self {
		$stringTime = sprintf('%02d:%02d:%02d.%d', $hour, $minute, $second, $millis);
		if (
			$hour < 0
			|| $hour > 23
			|| $minute < 0
			|| $minute > 59
			|| $second < 0
			|| $second > 59
			|| $millis < 0
		) {
			throw BadFormatException::create()
				->withMessage("Time parts are invalid: $stringTime");
		}

		return new self($hour, $minute, $second, $millis);
	}

	public static function createFromString(string $value): self
	{
		$exp = explode(":", $value);
		if( count($exp) === 2 || count($exp) === 3) {

			$hour = (int)$exp[0];
			$minute = (int)$exp[1];
			$second = $exp[2] ??= 0;
			$millis = $exp[3] ??= 0;

			return self::createFromParts($hour, $minute, (int)$second, (int)$millis);
		}

		throw BadFormatException::create()
			->withMessage("Time parts are invalid: $value");
	}

	public function getTimezone(): DateTimeZone
	{
		if (self::$dateTimeZone === NULL) {
			self::$dateTimeZone = new DateTimeZone(date_default_timezone_get());
		}

		return self::$dateTimeZone;
	}

	public function getHour(): int
	{
		return self::$hours;
	}

	public function getMinute(): int
	{
		return self::$minutes;
	}

	public function getSecond(): int
	{
		return self::$seconds;
	}

	public function getMillis(): int
	{
		return self::$millis;
	}
}

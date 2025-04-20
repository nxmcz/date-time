<?php

declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeZone;
use Exception;
use Noxem\DateTime\Attributes\Timezoned;
use Noxem\DateTime\Exception\BadFormatException;

class LocalTime
{
	use Timezoned;

	public const TimeSeparator = ':';

	private bool $hideSeconds = false;

	public function __construct(
		private int $hours,
		private int $minutes = 0,
		private int $seconds = 0,
		private int $millis = 0,
		private ?DateTimeZone $dateTimeZone = null
	) {
	}

	public function __toString(): string
	{
		$time = sprintf('%02d:%02d', $this->getHour(), $this->getMinute());
		if (! $this->hideSeconds) {
			$time .= sprintf(':%02d', $this->getSecond());
		}

		return $time;
	}

	public static function create(string|int|\DateTimeInterface|LocalTime $suspect = 'now', ?string $timezone = null): self
	{
		try {
			if ($suspect instanceof self) {
				$dt = $suspect;
				$millis = $dt->getMillis();
			} else {
				$dateTimeZone = new \DateTimeZone($timezone ?? date_default_timezone_get());
				$dt = DT::getOrCreateInstance($suspect)->setTimezone($dateTimeZone);
				$millis = $dt->getMillisPart();
			}

			return new self(
				$dt->getHour(),
				$dt->getMinute(),
				$dt->getSecond(),
				$millis,
				$dt->getTimezone()
			);
		} catch (Exception) {
			throw BadFormatException::create()
				->withMessage('Error DT format.');
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
		$exp = explode(self::TimeSeparator, $value);
		if (count($exp) === 2 || count($exp) === 3) {
			$hour = (int) $exp[0];
			$minute = (int) $exp[1];
			$second = $exp[2] ??= 0;
			$millis = $exp[3] ??= 0;

			return new self($hour, $minute, (int) $second, (int) $millis);
		}

		throw BadFormatException::create()
			->withMessage("Time parts are invalid: $value");
	}

	public function getHour(): int
	{
		return $this->hours;
	}

	public function getMinute(): int
	{
		return $this->minutes;
	}

	public function getSecond(): int
	{
		return $this->seconds;
	}

	public function getMillis(): int
	{
		return $this->millis;
	}

	public function hideSeconds(bool $hide = true): self
	{
		$clone = clone $this;
		$clone->hideSeconds = $hide;
		return $clone;
	}
}

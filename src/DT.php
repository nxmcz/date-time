<?php

declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeZone;
use Noxem\DateTime\Exception\InvalidArgumentException;

class DT extends \DateTimeImmutable
{
	use Attributes\Creation;
	use Attributes\DateConversion;
	use Attributes\Addition;
	use Attributes\Comparation;
	use Attributes\Casting;
	use Attributes\StatisticsCalculations;

	public function __toString(): string
	{
		return $this->toLocalHumanString();
	}

	public function isFuture(): bool
	{
		return $this->getTimestamp() > time();
	}

	public function difference(DT $suspect, bool $throw = false): Difference
	{
		return new Difference($this, $suspect, $throw);
	}

	public function clamp(DT|string|Difference $min, DT|string $max = null): self
	{
		if ($min instanceof Difference) {
			$max = $min->getEnd();
			$min = $min->getStart();
		} else {
			if ($max === null) {
				throw InvalidArgumentException::create()
					->withMessage('Max parameter is required if min is not Difference::class instance');
			}
		}

		return Utils\Helpers::clamp($this, $min, $max);
	}

	public function setTimeByInterface(\DateTimeInterface $interface): self
	{
		$dt = DT::createFromInterface($interface);
		return $this->setTime($dt->getHour(), $dt->getMinute(), $dt->getSecond());
	}

	public function setTimestamp($timestamp): self
	{
		$zone = $this->getTimezone();
		$datetime = new self('@' . (string) $timestamp);
		return $datetime->setTimezone($zone);
	}

	public function setTimezone(DateTimeZone|string|null $timezone = null): self
	{
		if ($timezone === null) {
			$timezone = date_default_timezone_get();
		}

		$tz = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
		return parent::setTimezone($tz);
	}

	public function assignTimezone(DateTimeZone|string $timezone): self
	{
		$tz = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
		$capture = $this->format(Utils\Formatter::TIMESTAMP_MILLIS);

		return new self($capture, $tz);
	}

	public function toLocalDate(): LocalDate
	{
		return new LocalDate($this);
	}

	public function toLocalTime(): LocalTime
	{
		return LocalTime::create($this);
	}
}

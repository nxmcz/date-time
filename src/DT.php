<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeZone;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Utils;

class DT extends \DateTimeImmutable
{
	use Attributes\Creation;
	use Attributes\DateConversion;
	use Attributes\Addition;
	use Attributes\Comparation;
	use Attributes\Casting;

	public function isFuture(): bool
	{
		return $this->getTimestamp() > time();
	}

	public function difference(DT $suspect, bool $throw = FALSE): Difference
	{
		return new Difference($this, $suspect, $throw);
	}

	public function clamp(\DateTimeInterface $min, \DateTimeInterface $max): self
	{
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

	public function setTimezone(DateTimeZone|string|null $timezone = NULL): self
	{
		if ($timezone === NULL) {
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

	public function getLocalTime(): LocalTime
	{
		return LocalTime::create($this);
	}

	public function getLocalDate(): LocalDate
	{
		return LocalDate::create($this);
	}

	public function __toString(): string
	{
		return $this->toLocalHumanString();
	}
}

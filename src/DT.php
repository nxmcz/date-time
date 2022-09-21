<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeImmutable as NativeDateTimeImmutable;
use DateTimeZone;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Utils;

class DT extends NativeDateTimeImmutable
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

	public function setTimestamp($timestamp): self
	{
		$zone = $this->getTimezone();
		$datetime = new self('@' . (string)$timestamp);
		return $datetime->setTimezone($zone);
	}

	public function setTimezone(DateTimeZone|string $timezone): self
	{
		$tz = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone ?? date_default_timezone_get());
		return parent::setTimezone($tz);
	}

	public function resetTimezone(DateTimeZone|string $timezone): self
	{
		$tz = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone($timezone);
		$capture = $this->format(Utils\Formatter::TIMESTAMP_MILLIS);

		return new self($capture, $tz);
	}

	public function __toString(): string
	{
		return $this->toLocalHumanString();
	}
}

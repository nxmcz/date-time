<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeImmutable as NativeDateTimeImmutable;
use DateTimeInterface;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Utils;


class DT extends NativeDateTimeImmutable implements \JsonSerializable
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

	public function resetTimezone(string $timezone): self
	{
		$capture = $this->format(Utils\Formatter::TIMESTAMP_MILLIS);
		return new self($capture, new \DateTimeZone($timezone));
	}

	public function __toString(): string {
		return $this->toLocalHumanString();
	}

	public function jsonSerialize(): mixed
	{
		return array_merge((array)$this, [
			"date" => $this->format(Utils\Formatter::TIMESTAMPS),
			"utc" => $this->format(Utils\Formatter::UTC),
			"atom" => $this->format(self::ATOM),
		]);
	}
}

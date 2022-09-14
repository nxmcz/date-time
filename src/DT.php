<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeImmutable as NativeDateTimeImmutable;
use DateTimeInterface;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Utils;


class DT extends NativeDateTimeImmutable
{
	use Attributes\Initialize;
	use Attributes\DateConversion;
	use Attributes\InputParser;

	public function areEquals(DateTimeInterface $suspect): bool
	{
		return $suspect->getTimestamp() === $this->getTimestamp()
			&& $suspect->getTimezone()->getName() === $this->getTimezone()->getName();
	}

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
		$zone = date_default_timezone_get();
		$datetime = new self('@' . (string)$timestamp);

		return $datetime->setTimezone(new \DateTimeZone($zone));
	}

	public function __toString(): string
	{
		return $this->format(Utils\Formatter::TIMESTAMPS);
	}

	public function convertInput(): Attributes\HtmlInputConversion {
		return new Attributes\HtmlInputConversion($this);
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeImmutable as NativeDateTimeImmutable;
use DateTimeInterface;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Utils;


class DT extends NativeDateTimeImmutable
{
	use Attributes\Initialize;
	use Attributes\Converts;
	use Attributes\ParseInput;

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
		$zone = $this->getTimezone();
		$datetime = new self('@' . (string)$timestamp);
		/*if ($zone === false) {
			throw new \RuntimeException('This DateTime object has no timezone.');
		}*/
		return $datetime->setTimezone($zone);
	}

	public function __toString(): string
	{
		return $this->format(Utils\Formatter::TIMESTAMPS);
	}
}

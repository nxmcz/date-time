<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeImmutable as NativeDateTimeImmutable;
use DateTimeInterface;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Exception\BadFormatException;


class DateTimeImmutable extends NativeDateTimeImmutable
{
	use Attributes\InitializeByInput;
	use Attributes\Converts;
	use Attributes\Overlapping;

	public static function create($suspect = 'now'): self
	{
		if ((int)$suspect >= 32400) {
			return (new self())->setTimestamp($suspect);
		}

		if($suspect !== 'now' && (bool)strtotime($suspect) === FALSE) {
			BadFormatException::create();
		}

		return new self($suspect);
	}

	public static function createFromParts(
		int   $year,
		int   $month,
		int   $day = 1,
		int   $hour = 0,
		int   $minute = 0,
		float $second = 0.0
	): self
	{
		$s = sprintf('%04d-%02d-%02d %02d:%02d:%02.5F', $year, $month, $day, $hour, $minute, $second);
		if (
			!checkdate($month, $day, $year)
			|| $hour < 0
			|| $hour > 23
			|| $minute < 0
			|| $minute > 59
			|| $second < 0
			|| $second >= 60
		) {
			throw new BadFormatException("Invalid date '$s'");
		}

		return new self($s);
	}

	public function areSame(NativeDateTimeImmutable $suspect): bool
	{
		return $suspect->getTimestamp() === $this->getTimestamp()
			&& $suspect->getTimezone()->getName() === $this->getTimezone()->getName();
	}

	public function isFuture(): bool
	{
		return $this->getTimestamp() > time();
	}

	public function difference(DateTimeInterface $suspect): Difference
	{
		return new Difference($this, $suspect);
	}

	public function term(): Term
	{
		return new Term($this);
	}

	public function setTimestamp($timestamp): self
	{
		$zone = $this->getTimezone();
		$datetime = new self('@' . (string)$timestamp);
		if ($zone === false) {
			throw new \RuntimeException('This DateTime object has no timezone.');
		}
		return $datetime->setTimezone($zone);
	}

	public function __toString(): string
	{
		return $this->format('Y-m-d H:i:s');
	}
}

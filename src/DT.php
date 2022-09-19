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

	/*public function areEquals(DateTimeInterface $suspect): bool
	{
		return $suspect->getTimestamp() === $this->getTimestamp()
			&& $suspect->getTimezone()->getName() === $this->getTimezone()->getName();
	}*/

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
		$datetime = new self($capture);
		return $datetime->setTimezone(new \DateTimeZone($timezone));
	}

	public function __toString(): string
	{
		return $this->format(Utils\Formatter::TIMESTAMPS);
	}

	public function toIso8601ZuluString(): string {
		return $this->setTimezone(new \DateTimeZone("UTC"))->format(Utils\Formatter::UTC);
	}

	public function toHtmlInput(): Attributes\HtmlInputConversion {
		return new Attributes\HtmlInputConversion($this);
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

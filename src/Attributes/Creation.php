<?php

declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;
use Nette\Utils\Validators;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;

/**
 * @internal
 */
trait Creation
{
	public static function create(string|int $suspect = 'now', ?string $timezone = null): self
	{
		if (Validators::isNumeric($suspect)) {
			return (new self())->setTimestamp((int) $suspect);
		}

		try {
			return (new self((string) $suspect))
				->setTimezone(new \DateTimeZone($timezone ?? date_default_timezone_get()));
		} catch (\Exception) {
			throw BadFormatException::create()
				->withMessage('Error DT format. Must be compatible with createFromFormat method.');
		}
	}

	public static function fromUTC(string $suspect): self
	{
		return (new self($suspect, new \DateTimeZone('UTC')))
			->setTimezone(date_default_timezone_get());
	}

	public static function createFromParts(
		int $year,
		int $month = 1,
		int $day = 1,
		int $hour = 0,
		int $minute = 0,
		float $second = 0.0
	): self {
		$s = sprintf('%04d-%02d-%02d %02d:%02d:%02.5F', $year, $month, $day, $hour, $minute, $second);
		if (
			!checkdate($month, $day, $year)
			|| $hour < 0
			|| $hour > 23
			|| $minute < 0
			|| $minute > 59
			|| $second < 0
			|| $second > 59
		) {
			throw BadFormatException::create()
				->withMessage("This date parts are invalid: $s");
		}

		return new self($s);
	}

	/**
	 * Equivalent to create without params
	 */
	public static function now(): self
	{
		return self::create();
	}

	public static function createFromInterface(DateTimeInterface $immutable): self
	{
		return self::create($immutable->getTimestamp())->setTimezone($immutable->getTimezone());
	}

	public static function getOrCreateInstance(null|string|int|DateTimeInterface $suspect = null): self
	{
		return match (true) {
			$suspect instanceof DT => $suspect->setTimezone(date_default_timezone_get()),
			$suspect instanceof DateTimeInterface => self::createFromInterface($suspect),
			default => self::create($suspect ?? 'now')
		};
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils;


/**
 * @internal
 */
trait Creation
{
	public static function create(string|int|float $suspect = 'now'): self {

		if (Utils\Validators::isTimestamp($suspect)) {
			return (new self())->setTimestamp((int) $suspect);
		}

		/*if (Utils\Validators::isDate($suspect) === FALSE) {
			if ($throw === TRUE) {
				throw BadFormatException::create()
					->withMessage("Error format is: $suspect");
			}

			$suspect = $defaultValue ?? 'now';
		}*/

		try {
			return new self((string) $suspect);
		} catch (\Exception) {
			throw BadFormatException::create()
				->withMessage("Error format is: $suspect");
		}
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

	public static function getOrCreateInstance(string|int|float|null|DateTimeInterface $suspect = NULL): self
	{
		return match (TRUE) {
			$suspect instanceof DT => $suspect,
			$suspect instanceof DateTimeInterface => self::createFromInterface($suspect),
			default => self::create($suspect ?? 'now')
		};
	}

}

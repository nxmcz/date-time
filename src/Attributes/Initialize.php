<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils;

/**
 * @internal
 */
trait Initialize
{
	/**
	 * Creates NOXEM DT object directly
	 *
	 * @param string|int|float|NULL      $suspect
	 * @param bool                       $throw
	 * @param string|int|float|NULL|null $defaultValue
	 */
	public static function create(
		string|int|float|NULL $suspect = 'now',
		bool $throw = TRUE,
		string|int|float|NULL $defaultValue = NULL
	): self {
		if (Utils\Validators::isTimestamp($suspect)) {
			return (new self())->setTimestamp((int)$suspect);
		}

		$suspect = (string)$suspect;

		if ( Utils\Validators::isDate($suspect) === FALSE ) {
			if ($throw === TRUE) {
				BadFormatException::create();
			}

			$suspect = $defaultValue ?? 'now';
		}

		return new self((string) $suspect);
	}

	/**
	 * Creates NOXEM DT object from parts of date
	 * !!!!!!! EDITED FUNCTION PART OF NETTE\UTILS\DATETIME !!!!!!!
	 *
	 * @param int   $year
	 * @param int   $month
	 * @param int   $day
	 * @param int   $hour
	 * @param int   $minute
	 * @param float $second
	 */
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

	/**
	 * @param string $value
	 */
	public static function createByMonth(string $value): self
	{
		list($year, $month) = explode("-", $value);
		return self::createFromParts((int)$year, (int)$month);
	}

	/**
	 * @param string|int|float|null|DateTimeInterface $suspect
	 */
	public static function getOrCreateInstance(string|int|float|null|DateTimeInterface $suspect = NULL): self
	{
		return match(TRUE) {
			$suspect instanceof DT => $suspect,
			$suspect instanceof DateTimeInterface => self::create($suspect->getTimestamp())->setTimezone($suspect->getTimezone()),
			default => self::create($suspect ?? 'now')
		};
	}

}

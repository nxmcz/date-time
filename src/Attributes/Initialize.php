<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils;


trait Initialize
{
	public static function create(string|int $suspect = 'now', bool $throw = TRUE, $defaultValue = NULL): self
	{
		if ( Utils\Validators::isTimestamp($suspect) ) {
			return (new self())->setTimestamp((int)$suspect);
		}

		$suspect = (string)$suspect;

		if(
			($suspect !== 'now' && (bool)strtotime($suspect) === FALSE)
		) {
			if($throw === TRUE) {
				BadFormatException::create();
			}

			$suspect = $defaultValue;
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

	public static function createByMonth(string $value): self {
		list($year, $month) = explode("-", $value);
		return self::createFromParts((int)$year, (int)$month);
	}

	public static function getOrCreateInstance($suspect = 'now'): self
	{
		if($suspect instanceof DT) {
			return $suspect;
		}

		return self::create($suspect);
	}

}

<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\DT;

class Validator
{
	public static function isWeekend(\DateTimeInterface $value): bool
	{
		return in_array((int) date('N', $value->getTimestamp()), [6,7]);
	}
}

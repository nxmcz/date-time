<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


trait InitializeByInput
{
	public static function createByMonth(string $value): self {
		list($year, $month) = explode("-", $value);
		return self::createFromParts((int)$year, (int)$month);
	}
}

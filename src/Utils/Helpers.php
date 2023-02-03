<?php

declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\DT;

class Helpers
{
	public static function clamp(DT|string $value, DT|string $min, DT|string $max): DT
	{
		return DT::create()
			->setTimestamp(
				max(
					DT::getOrCreateInstance($min)->getTimestamp(),
					min(DT::getOrCreateInstance($max)->getTimestamp(), DT::getOrCreateInstance($value)->getTimestamp())
				)
			);
	}
}

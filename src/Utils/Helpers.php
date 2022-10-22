<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\DT;

class Helpers
{
	public static function clamp(\DateTimeInterface $value, \DateTimeInterface $min, \DateTimeInterface $max): DT
	{
		return DT::create()
			->setTimestamp(
				max(
					$min->getTimestamp(),
					min($max->getTimestamp(), $value->getTimestamp())
				)
			);
	}
}

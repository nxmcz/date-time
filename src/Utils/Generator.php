<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;
use Traversable;

class Generator
{
	/**
	 * @param DT|null $dt
	 * @return Traversable<LocalDate>
	 */
	public static function week(DT $dt = NULL): Traversable
	{
		$dt ??= DT::create("last monday");

		for ($i = 0; $i < 7; $i++) {
			yield $dt->addDays($i)->toLocalDate();
		}
	}
}
<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate\Day;

class Generator
{
	/**
	 * @param DT|null $dt
	 * @return \Generator
	 */
	public static function week(DT $dt = null): \Generator
	{
		$dt ??= DT::create('last monday');

		for ($i=0; $i<7; $i++) {
			yield $dt->addDays($i)->getLocalDate();
		}
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate\Day;

class Generator
{
	/**
	 * @params DT|null $dt
	 * @return array<Day>
	 */
	public static function week(DT $dt = NULL): array
	{
		$generator = array();
		$dt ??= DT::create("last monday");

		for ($i=0;$i<7;$i++) {
			$generator[] = $dt->addDays($i)->getLocalDate();
		}

		return $generator;
	}
}

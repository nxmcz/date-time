<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;

class Day extends DatePart
{
	public function getMaximumNumber(): int
	{
		return (int) $this->dt->format('t');
	}

	public function getNumber(): int
	{
		return $this->dt->getDay();
	}

	public function getDayOfWeek(): int
	{
		return $this->dt->getDayOfWeek();
	}

	public function getStartingWeekOffset(): int
	{
		return $this->dt->getDayOfWeek() - 1;
	}

	public function getEndingWeekOffset(): int
	{
		return 7 - $this->dt->getDayOfWeek();
	}

	public function getFormat(): string
	{
		return Formatter::DAY_NAME;
	}

	/**
	 * @params DT|null $dt
	 * @return array<Day>
	 */
	public static function generate(DT $dt = null): array
	{
		$generator = [];
		$dt ??= DT::create();
		$maximum = $dt->getLocalDate()->getDay()->getMaximumNumber();

		$first = $dt
			->setDate($dt->getYear(), $dt->getMonth(), 1)
			->setTime(0, 0);

		for ($i=0; $i<$maximum; $i++) {
			$generator[] = $first->addDays($i)->getLocalDate()->getDay();
		}

		return $generator;
	}

	public function difference(): Difference
	{
		$start = $this->getDT()->setTime(0, 0);
		return new Difference($start, $start->addDays(1));
	}
}

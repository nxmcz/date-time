<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\DT;
use Noxem\DateTime\Period;
use Noxem\DateTime\LocalDate;

class DayDifference extends PeriodDifference
{
	public function __construct(DT $start)
	{
		$dt = $start->setTime(0, 0);
		parent::__construct($dt, $dt->modifyDays(1), Period::DAY);
	}

	public function __invoke(): LocalDate\Day
	{
		$dt = $this->getStart();
		return new LocalDate\Day($dt->getYear(), $dt->getMonth(), $dt->getDay());
	}
}

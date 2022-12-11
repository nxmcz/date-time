<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;
use Noxem\DateTime\Period;

class DayDifference extends PeriodDifference
{
	public function __construct(DT $start, DT $end = null)
	{
		parent::__construct($start, $end ?? $start->modifyDays(1), Period::DAY);
	}

	public function __invoke(): LocalDate\Day
	{
		$dt = $this->getStart();
		return new LocalDate\Day($dt->getYear(), $dt->getMonth(), $dt->getDay());
	}
}

<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\DT;
use Noxem\DateTime\Period;
use Noxem\DateTime\LocalDate;

class MonthDifference extends PeriodDifference
{
	public function __construct(DT $start)
	{
		$dt = $start->setTime(0, 0);
		parent::__construct($dt, $dt->modifyMonths(1), Period::MONTH);
	}

	public function __invoke(): LocalDate\Month
	{
		$dt = $this->getStart();
		return new LocalDate\Month($dt->getYear(), $dt->getMonth());
	}
}

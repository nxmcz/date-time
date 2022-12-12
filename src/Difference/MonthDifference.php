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
		parent::__construct($start, $start, Period::MONTH);
	}

	public function toLocal(): LocalDate\Month
	{
		$dt = $this->getStart();
		return new LocalDate\Month($dt->getYear(), $dt->getMonth());
	}
}

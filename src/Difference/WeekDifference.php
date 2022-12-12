<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\DT;
use Noxem\DateTime\Period;
use Noxem\DateTime\LocalDate;

class WeekDifference extends PeriodDifference
{
	public function __construct(DT $start)
	{
		parent::__construct($start, $start, Period::WEEK);
	}

	public function toLocal(): LocalDate\Week
	{
		$dt = $this->getStart();
		return new LocalDate\Week($dt->getYear(), $dt->getWeek());
	}
}

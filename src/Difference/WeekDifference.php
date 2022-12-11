<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\DT;
use Noxem\DateTime\Interval;
use Noxem\DateTime\LocalDate;

class WeekDifference extends PeriodDifference
{
	public function __construct(DT $start)
	{
		$dt = $start->setTime(0, 0);
		parent::__construct($dt, $dt->modifyDays(7), Interval::WEEK);
	}

	public function __invoke(): LocalDate\Week
	{
		$dt = $this->getStart();
		return new LocalDate\Week($dt->getYear(), $dt->getWeek());
	}
}

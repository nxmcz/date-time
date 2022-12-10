<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;

class DayDifference extends Difference
{
	public function __construct(DT $start)
	{
		$dt = $start->setTime(0, 0);
		parent::__construct($dt, $dt->modifyDays(1));
	}

	public function __invoke(): LocalDate
	{
		return LocalDate::create($this->getStart());
	}
}

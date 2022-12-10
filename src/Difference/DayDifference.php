<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\Difference;
use Noxem\DateTime\LocalDate;

class DayDifference extends Difference
{
	public function __invoke(): LocalDate
	{
		return LocalDate::create($this->getStart());
	}
}

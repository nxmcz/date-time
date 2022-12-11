<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Period;

class PeriodDifference extends Difference
{
	private ?Period $period;

	public function __construct(
		DT $start,
		DT $end,
		Period $period = null
	) {
		parent::__construct($start, $end);
		$this->period = $period;
	}

	public static function createByDifference(Difference $d, Period $interval = null): self
	{
		return new self($d->getStart(), $d->getEnd(), $interval);
	}

	public function withInterval(Period $interval): self
	{
		$clone = $this;
		$clone->period = $interval;
		return $clone;
	}

	public function getPeriod(): Period
	{
		return $this->period ?? Period::DAY;
	}

	public function withStandardizeTimes(): self
	{
		$clone = $this;
		$period = $clone->period;
		$clone->start = $clone->start->setTime(0, 0);
		$clone->end = $clone->end->add($period->getInterval())->setTime(0, 0);

		if ($period === Period::MONTH) {
			$clone->start = $clone->start->setDate(
				$clone->start->getYear(),
				$clone->start->getMonth(),
				1
			);
		}

		if ($period === Period::WEEK) {
			$clone->start = $clone->start->modify('monday this week');
		}

		return $clone;
	}
}

<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Interval;

class PeriodDifference extends Difference
{
	private ?Interval $interval;

	public function __construct(
		DT $start,
		DT $end,
		Interval $interval = null
	) {
		parent::__construct($start, $end);
		$this->interval = $interval;
	}

	public static function createByDifference(Difference $d, Interval $interval = null): self
	{
		return new self($d->getStart(), $d->getEnd(), $interval);
	}

	public function withInterval(Interval $interval): self
	{
		$clone = $this;
		$clone->interval = $interval;
		return $clone;
	}

	public function getPeriod(): Interval
	{
		return $this->interval ?? Interval::DAY;
	}
}

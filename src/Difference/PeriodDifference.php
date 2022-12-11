<?php

declare(strict_types=1);

namespace Noxem\DateTime\Difference;

use IteratorAggregate;
use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Period;
use Traversable;

/**
 * @implements IteratorAggregate<PeriodDifference>
 * @phpstan-consistent-constructor
 */
class PeriodDifference extends Difference implements IteratorAggregate
{
	public function __construct(DT $start, DT $end, private readonly Period $period)
	{
		$this->start = $start->setTime(0, 0);
		$this->end = $end->setTime(0, 0)->add($period->getInterval());

		if ($period === Period::YEAR) {
			$this->start = $this->start->setDate(
				$this->start->getYear(),
				1,
				1
			);
		}

		if ($period === Period::MONTH) {
			$this->start = $this->start->setDate(
				$this->start->getYear(),
				$this->start->getMonth(),
				1
			);

			$this->end = $this->end->modify('first day of this month');
		}

		if ($period === Period::WEEK) {
			$this->start = $this->start->modify('monday this week');
		}

		parent::__construct($this->start, $this->end);
	}

	public static function createByDifference(Difference $d, Period $interval = null): self
	{
		return new self($d->getStart(), $d->getEnd(), $interval);
	}

	public function getAccumulationDifference(): Difference
	{
		return new Difference($this->getStart(), $this->getEnd()->add($this->getPeriod()->getInterval()));
	}

	public function getPeriod(): Period
	{
		return $this->period;
	}

	public function getIterator(): Traversable
	{
		$interval = $this->getPeriod()->getInterval();
		$new = $this->start;
		$cannotAcross = $this->end;

		while ($cannotAcross->isGreaterThan($new)) {
			yield new static($new, $new, $this->getPeriod());
			$new = $new->add($interval);
		}
	}
}

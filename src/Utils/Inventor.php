<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use Noxem\DateTime\Difference\PeriodDifference;
use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate\DatePart;
use Noxem\DateTime\LocalDate\Day;
use Noxem\DateTime\LocalDate\Week;
use Noxem\DateTime\Period;
use Traversable;

class Inventor implements \IteratorAggregate
{
	public function __construct(
		private readonly ?DatePart $from,
		private readonly ?DatePart $to,
		private readonly Period $period
	)
	{
	}

	public static function week(Week $from = null, Week $to = null): Inventor
	{
		return new self($from, $to, Period::WEEK);
	}

	public static function day(Day $from = null, Day $to = null): Inventor
	{
		return new self($from, $to, Period::DAY);
	}

	public function getFrom(): DT {
		return $this->from?->getDT() ?? DT::now();
	}

	public function getTo(): DT {
		return $this->to?->getDT() ?? DT::now();
	}

	public function difference(): PeriodDifference
	{
		return new PeriodDifference($this->getFrom(), $this->getTo(), $this->period);
	}

	public function getIterator(): Traversable
	{
		$interval = $this->period->getInterval();
		$new = $this->getFrom();
		$cannotAcross = $this->getTo();

		/** @var DatePart $class */
		$class = $this->period->getClassInstance();

		while ($cannotAcross->isGreaterThanOrEqualTo($new)) {
			yield $class::createFromDT($new);
			$new = $new->add($interval);
		}
	}
}

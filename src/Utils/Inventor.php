<?php

declare(strict_types=1);

namespace Noxem\DateTime\Utils;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Noxem\DateTime\Difference\PeriodDifference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\LocalDate\DatePart;
use Noxem\DateTime\LocalDate\Day;
use Noxem\DateTime\LocalDate\Month;
use Noxem\DateTime\LocalDate\Week;
use Noxem\DateTime\Period;
use Traversable;

/**
 * @implements IteratorAggregate<int, DatePart>
 */
class Inventor implements IteratorAggregate, Countable
{
	/** @var array<int, DatePart> */
	private array $items = [];

	public function __construct(
		private readonly DatePart $from,
		private readonly DatePart $to,
		private readonly Period $period
	) {
		if ($this->getFrom()->isGreaterThan($this->getTo())) {
			throw BadFormatException::create()->withMessage('To parameter cannot be greater than from.');
		}

		$this->make();
	}

	public static function day(DatePart $from = null, DatePart $to = null): self
	{
		return new self($from, $to, Period::DAY);
	}

	public static function week(DatePart $from = null, DatePart $to = null): self
	{
		return new self($from, $to, Period::WEEK);
	}

	public static function month(DatePart $from = null, DatePart $to = null): self
	{
		return new self($from, $to, Period::MONTH);
	}

	public function getFrom(): DT
	{
		return $this->from->getDT();
	}

	public function getTo(): DT
	{
		return $this->to->getDT();
	}

	public function difference(): PeriodDifference
	{
		return new PeriodDifference($this->getFrom(), $this->getTo(), $this->period);
	}

	public function count(): int
	{
		return count($this->items);
	}

	public function first(): ?DT
	{
		return $this->items[0]->difference()->getStart() ?? null;
	}

	public function last(): ?DT
	{
		return $this->items[$this->count()-1]->difference()->getStart() ?? null;
	}

	public function getIterator(): Traversable
	{
		return new ArrayIterator($this->items);
	}

	private function make(): void
	{
		$interval = $this->period->getInterval();
		$new = $this->getFrom();
		$cannotAcross = $this->to->difference()->getEnd(); // exception state

		/** @var DatePart $class */
		$class = $this->period->getClassInstance();

		while ($cannotAcross->isGreaterThan($new)) {
			$this->items[] = $class::createFromDT($new);
			$new = $new->add($interval);
		}
	}
}

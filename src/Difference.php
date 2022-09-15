<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateInterval as NativeDateInterval;
use DateTimeInterface as NativeDateTimeInterface;
use Noxem\DateTime\Attributes;


class Difference implements Attributes\Intervalic
{
	use Attributes\TimeConversion;

	public DT $start;
	public DT $end;
	private bool $absoluteCalculation = FALSE;

	public function __construct(
		DT|int $start,
		DT|int $end,
		bool $throw = FALSE
	) {
		$this->start = DT::getOrCreateInstance($start);
		$this->end = DT::getOrCreateInstance($end);

		if ($throw === TRUE && $this->isValid() === FALSE) {
			throw new \InvalidArgumentException("Start of the interval cannot be greater than end.");
		}
	}

	public function withAbsolute(): self
	{
		$difference = clone $this;
		$difference->absoluteCalculation = TRUE;
		return $difference;
	}

	public function isValid(): bool
	{
		return ($this->getEndTimestamp() - $this->getStartTimestamp()) > 0;
	}

	public function getStart(): DT
	{
		return $this->start;
	}

	public function getEnd(): DT
	{
		return $this->end;
	}

	public function getStartTimestamp(): int
	{
		return $this->start->getTimestamp();
	}

	public function getEndTimestamp(): int
	{
		return $this->end->getTimestamp();
	}

	public function isActive(): bool
	{
		return time() >= $this->getStartTimestamp()
			&& time() < $this->getEndTimestamp();
	}

	public function intervalToNow(DT|int|null $now = NULL): int
	{
		return (DT::getOrCreateInstance($now ?? 'now')->getTimestamp()) - $this->getStartTimestamp();
	}

	public function intervalToEnd(DT|int|null $now = NULL): int
	{
		return $this->getEndTimestamp() - (DT::getOrCreateInstance($now ?? 'now')->getTimestamp());
	}

	public function solidWeeks(): int
	{
		$date1 = $this->getStart()->setTime(0,0);
		$date2 = $this->getEnd()->setTime(0,0);

		$week1 = idate('W', $date1->getTimestamp());
		$week2 = idate('W', $date2->getTimestamp());

		if($this->absoluteCalculation && $week1 > $week2) {
			$_temp = $week2;
			$week2 = $week1;
			$week1 = $_temp;
		}

		$multiplier = ($week1 > $week2 ? (-1) : 1);

		if($date2->format('Y') === $date1->format('Y')) {
			return ($week2 - $week1);
		}

		$diff = date_diff( $date2->modify("monday this week"), $date1->modify("monday this week"));
		return (int)($diff->days / 7) * $multiplier;
	}

	public function days(): int
	{
		$res = (int)$this->getInterval()->format("%r%a");
		return $this->absoluteCalculation ? abs($res) : $res;
	}

	public function getInterval(): NativeDateInterval
	{
		$clone = clone $this->getStart();
		return $clone->diff($this->getEnd());
	}

	public function createBaseForTimeConversion(): int
	{
		$res = $this->getEndTimestamp() - $this->getStartTimestamp();
		return $this->absoluteCalculation ? abs($res) : $res;
	}
}

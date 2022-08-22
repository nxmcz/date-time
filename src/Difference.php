<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateInterval as NativeDateInterval;
use DateTimeInterface as NativeDateTimeInterface;
use Noxem\DateTime\Attributes;


class Difference implements Attributes\Intervalic
{
	public DT $start;
	public DT $end;

	public function __construct(
		NativeDateTimeInterface|int $start,
		NativeDateTimeInterface|int $end,
		bool $throw = FALSE
	) {
		$this->start = DT::getOrCreateInstance($start);
		$this->end = DT::getOrCreateInstance($end);

		if ($throw === TRUE && $this->isValid() === FALSE) {
			throw new \InvalidArgumentException("Start of the interval cannot be greater than end.");
		}
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

	public function intervalToNow($now = NULL): int
	{
		return ($now ?? time()) - $this->getStartTimestamp();
	}

	public function intervalToEnd($now = NULL): int
	{
		return $this->getEndTimestamp() - ($now ?? time());
	}

	public function weeks(): int
	{
		$date1 = $this->getStart()->setTime(0,0);
		$date2 = $this->getEnd()->setTime(0,0);

		$week1 = idate('W', $date1->getTimestamp());
		$week2 = idate('W', $date2->getTimestamp());

		if($date2->format('Y') === $date1->format('Y')) {
			return abs($week2 - $week1);
		}

		$diff = date_diff( $date2->modify("monday this week"), $date1->modify("monday this week"));
		return (int)($diff->days / 7);
	}

	public function days(): int
	{
		return (int)$this->interval()->format("%r%a");
	}

	public function seconds(): int
	{
		return $this->getInterval();
	}

	public function msec(): int
	{
		return $this->seconds()*1000;
	}

	public function minutes(): float
	{
		return $this->getInterval() / 60;
	}

	public function hours(): float
	{
		return $this->getInterval() / 3600;
	}

	public function getInterval(): int {
		return abs($this->getEndTimestamp() - $this->getStartTimestamp());
	}

	public function interval(): NativeDateInterval
	{
		$clone = clone $this->getStart();
		return $clone->diff($this->getEnd());
	}
}

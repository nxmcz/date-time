<?php

declare(strict_types=1);

namespace Noxem\DateTime;

use DateInterval as NativeDateInterval;
use Noxem\DateTime\Attributes;

class Difference implements Attributes\Intervalic, \JsonSerializable
{
	use Attributes\TimeConversion;

	public DT $start;

	public DT $end;

	protected bool $absoluteCalculation = false;

	public function __construct(
		DT|string|int $start,
		DT|string|int $end,
		bool $throw = false
	) {
		$this->start = DT::getOrCreateInstance($start);
		$this->end = DT::getOrCreateInstance($end);

		if ($throw === true && $this->isValid() === false) {
			throw new \InvalidArgumentException('Start of the interval cannot be greater than end.');
		}
	}

	public function withAbsolute(): self
	{
		$difference = clone $this;
		$difference->absoluteCalculation = true;
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

	public function intervalToNow(DT|int|null $now = null): int
	{
		return (DT::getOrCreateInstance($now ?? 'now')->getTimestamp()) - $this->getStartTimestamp();
	}

	public function intervalToEnd(DT|int|null $now = null): int
	{
		return $this->getEndTimestamp() - (DT::getOrCreateInstance($now ?? 'now')->getTimestamp());
	}

	public function getSolidWeeks(): int
	{
		$start = $this->getStart()->setTime(0, 0);
		$end = $this->getEnd()->setTime(0, 0);
		$multiplier = ($start->getTimestamp() > $end->getTimestamp()) ? -1 : 1;

		$week1 = $start->getWeek();
		$week2 = $end->getWeek();

		if ($start->getYear() === $end->getYear()) {
			$res = abs($week2 - $week1)*$multiplier;
		} else {
			$diff = date_diff($end->modify('monday this week'), $start->modify('monday this week'));
			$res = (int) ($diff->days / 7) * $multiplier;
		}

		return $this->absoluteCalculation ? abs($res) : $res;
	}

	public function getWeeks(): float
	{
		return (float) ($this->getSeconds()/604800);
	}

	public function getDays(): int
	{
		$res = (int) $this->getInterval()->format('%r%a');
		return $this->absoluteCalculation ? abs($res) : $res;
	}

	public function getInterval(): NativeDateInterval
	{
		$clone = clone $this->getStart();
		return $clone->diff($this->getEnd());
	}

	public function createBaseForTimeConversion(): int
	{
		$res = $this->intervalToSeconds($this->getInterval());
		$sign = $this->getEndTimestamp() > $this->getStartTimestamp();
		return $this->absoluteCalculation ? $res : $res * ($sign ? 1 : (-1));
	}

	public function isDayFlip(): bool
	{
		return $this->getStart()->getAbsoluteDate() !== $this->getEnd()->getAbsoluteDate();
	}

	/**
	 * @return array<string, float|int|DT>>
	 */
	public function jsonSerialize(): array
	{
		return [
			'start' => $this->getStart(),
			'end' => $this->getEnd(),
			'millis' => $this->getMillis(),
			'seconds' => $this->getSeconds(),
			'minutes' => $this->getMinutes(),
			'hours' => $this->getHours(),
			'days' => $this->getDays(),
			'weeks' => $this->getWeeks(),
			'solidWeeks' => $this->getSolidWeeks(),
		];
	}

	public function clamp(Difference $borders): self
	{
		return new self(
			$this->getStart()->clamp($borders->getStart(), $borders->getEnd()),
			$this->getEnd()->clamp($borders->getStart(), $borders->getEnd()),
		);
	}

	private function intervalToSeconds(NativeDateInterval $interval): int
	{
		return $interval->days * 86400 + $interval->h * 3600 + $interval->i * 60 + $interval->s;
	}
}

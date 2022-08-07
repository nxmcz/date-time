<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateInterval;
use DateTimeInterface;
use Exception;


class Difference
{
	public function __construct(
		private DateTimeInterface $start,
		private DateTimeInterface $end,
	)
	{
		if ($this->start->getTimestamp() > $this->end->getTimestamp()) {
			throw new \InvalidArgumentException("Start of the interval cannot be greater than end.");
		}
	}


	public function isValid(): bool
	{
		return !($this->getStart() - $this->getEnd() === 0);
	}

	/**
	 * @throws Exception
	 */
	public function getStartDT(): DateTimeInterface
	{
		return $this->start;
	}


	/**
	 * @throws Exception
	 */
	public function getEndDT(): DateTimeInterface
	{
		return $this->end;
	}


	/**
	 * @throws Exception
	 */
	public function getStart(): int
	{
		return $this->start->getTimestamp();
	}


	/**
	 * @return int
	 * @throws Exception
	 */
	public function getEnd(): int
	{
		return $this->end->getTimestamp();
	}

	/**
	 * @throws Exception
	 */
	public function getStartMilis(): int
	{
		return $this->start->getTimestamp() * 1000;
	}


	/**
	 * @return int
	 * @throws Exception
	 */
	public function getEndMilis(): int
	{
		return $this->end->getTimestamp() * 1000;
	}


	public function getInterval(): int
	{
		return abs($this->getEnd() - $this->getStart());
	}


	/**
	 * @throws Exception
	 */
	public function getIntervalDT(): DateInterval
	{
		$clone = clone $this->getEndDT();
		return $clone->diff($this->getStartDT());
	}


	public function seconds(): int
	{
		return $this->getInterval();
	}


	public function msec(): int
	{
		return $this->seconds() * 1000;
	}

	public function minutes(): float
	{
		return $this->getInterval() / 60;
	}

	public function hours(): float
	{
		return $this->getInterval() / 3600;
	}

	public function isActive(): bool
	{
		return time() >= $this->getStart() && time() < $this->getEnd();
	}

	public function intervalToNow(): int
	{
		return time() - $this->start->getTimestamp();
	}

	public function intervalToEnd(): int
	{
		return $this->end->getTimestamp() - time();
	}
}

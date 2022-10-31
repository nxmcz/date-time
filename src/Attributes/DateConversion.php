<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


use Noxem\DateTime\Utils\Formatter;

trait DateConversion
{
	public function getMillis(): int {
		return $this->getTimestamp() * 1000;
	}

	public function getMillisPart(): int {
		return (int)$this->format(Formatter::MILLIS);
	}

	public function getWeek(): int {
		return (int)$this->format('W');
	}

	public function getMinute(): int {
		return (int)$this->format('i');
	}

	public function getHour(): int {
		return (int)$this->format('H');
	}

	public function getDay(): int {
		return (int)$this->format('d');
	}

	public function getMonth(): int {
		return (int)$this->format('m');
	}

	public function getWeeksOfYear(): int {
		return (int)static::createFromParts($this->getYear(), 12, 28)->format("W");
	}

	/**
	 * @param bool $iso ISO-8601 numeric representation, 1 (for Monday) through 7 (for Sunday)
	 *      when $iso is FALSE = 1 (for Sunday) through 7 (for Saturday)
	 * @return int
	 */
	public function getDayOfWeek(bool $iso = TRUE): int {
		return $iso ? (int)$this->format('N') : (int)$this->format('w')+1;
	}

	public function getDayOfYear(): int {
		return (int)$this->format('z')+1;
	}

	public function getDaysOfYear(): int {
		return 365 + ($this->isYearLeap() ? 1 : 0);
	}

	public function isYearLeap(): bool {
		return (bool)$this->format('L');
	}

	public function getYear(): int {
		return (int)$this->format('Y');
	}
}

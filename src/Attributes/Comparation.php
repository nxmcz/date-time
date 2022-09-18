<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;

/**
 * @internal
 */
trait Comparation
{
	public function areEquals(DateTimeInterface $b): bool
	{
		return $this->compare($b) === 0;
	}

	public function areNotEquals(DateTimeInterface $b): bool
	{
		return !($this->compare($b) === 0);
	}

	/**
	 * Compare two datetime objects (depends on timezone too)
	 */
	public function compare(DateTimeInterface $suspect): int
	{
		$a = $this->getTimestamp() + ($suspect->getOffset() - $this->getOffset());
		$suspect = $suspect->getTimestamp();

		if (abs($a - $suspect) === 0) {
			return 0;
		}

		return $a < $suspect ? -1 : 1;
	}

	public function isLessThan(DateTimeInterface $b): bool
	{
		return $this->compare($b) < 0;
	}

	public function isLessThanOrEqualTo(DateTimeInterface $b): bool
	{
		return $this->compare($b) <= 0;
	}

	public function isGreaterThan(DateTimeInterface $b): bool
	{
		return $this->compare($b) > 0;
	}

	public function isGreaterThanOrEqualTo(DateTimeInterface $b): bool
	{
		return $this->compare($b) >= 0;
	}
}

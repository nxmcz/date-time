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
		$zone = new \DateTimeZone(date_default_timezone_get());
		$current = $this->setTimezone($zone)->getTimestamp();
		$suspect = self::createFromInterface($suspect)->setTimezone($zone)->getTimestamp();

		if (abs($current - $suspect) === 0) {
			return 0;
		}

		return $current < $suspect ? -1 : 1;
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

	public function getTimezoneOffset(DateTimeInterface $b): int
	{
		return (int)(($b->getOffset() - $this->getOffset())/3600);
	}
}

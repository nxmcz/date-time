<?php declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Utils\Formatter;

class Day extends DatePart
{
	public function getMaximumNumber(): int
	{
		return (int)$this->dt->format("t");
	}

	public function getNumber(): int
	{
		return $this->dt->getDay();
	}

	public function getDayOfWeek(): int
	{
		return $this->dt->getDayOfWeek();
	}

	public function getStartingWeekOffset(): int
	{
		return $this->dt->getDayOfWeek() - 1;
	}

	public function getEndingWeekOffset(): int
	{
		return 7 - $this->dt->getDayOfWeek();
	}

	public function getFormat(): string
	{
		return Formatter::DAY_NAME;
	}
}

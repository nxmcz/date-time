<?php

declare(strict_types=1);

namespace Noxem\DateTime;

use Noxem\DateTime\LocalDate\Day;
use Noxem\DateTime\LocalDate\Month;
use Noxem\DateTime\LocalDate\Week;

class LocalDate
{
	public function __construct(
		private readonly DT $dtObject
	) {
	}

	public function __toString(): string
	{
		return $this->getDT()->toHtmlDate();
	}

	public function getMonth(): Month
	{
		return Month::createFromDT($this->getDT());
	}

	public function getWeek(): Week
	{
		return Week::createFromDT($this->getDT());
	}

	public function getDay(): Day
	{
		return Day::createFromDT($this->getDT());
	}

	public function getDT(): DT
	{
		return $this->dtObject;
	}
}
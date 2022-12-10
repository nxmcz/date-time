<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use DateTimeInterface;
use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;
use Noxem\DateTime\Utils\Parser;

class Week extends DatePart
{
	public function getMaximumNumber(): int
	{
		return $this->dt->getWeeksOfYear();
	}

	public function getNumber(): int
	{
		return $this->dt->getWeek();
	}

	public function getFormat(): string
	{
		return Formatter::WEEK_NUM;
	}

	public function difference(): Difference
	{
		return new Difference($this->getDT(), $this->getDT()->addDays(7));
	}

	public function createFromHtml(string|null|DateTimeInterface $value): self
	{
		$parse = Parser::fromWeek($value);
		return new self($parse);
	}
}

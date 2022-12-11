<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils\Formatter;
use Noxem\DateTime\Utils\Parser;

class Week extends DatePart
{
	private int $week;

	private int $year;

	public function __construct(int $year, int $week)
	{
		$dt = Parser::fromWeek(sprintf('%s-W%s', $year, $week));

		if ($dt === null) {
			throw BadFormatException::create();
		}

		$this->setDT($dt);
		$this->week = $this->getDT()->getWeek();
		$this->year = $this->getDT()->getYear();
	}

	public function __toString(): string
	{
		return $this->getDT()->toHtmlWeek();
	}

	public function getMaximumNumber(): int
	{
		return $this->getDT()->getWeeksOfYear();
	}

	public function getNumber(): int
	{
		return $this->week;
	}

	public function getYear(): int
	{
		return $this->year;
	}

	public function difference(): Difference
	{
		return new Difference($this->getDT(), $this->getDT()->addDays(6));
	}

	public static function createFromHtml(string $html): self
	{
		$parse = Parser::fromWeek($html);

		if ($parse === null) {
			throw BadFormatException::create();
		}

		return new self($parse->getYear(), $parse->getWeek());
	}
}

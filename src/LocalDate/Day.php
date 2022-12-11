<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils\Formatter;
use Noxem\DateTime\Utils\Parser;

class Day extends DatePart
{
	private int $day;

	private int $month;

	private int $year;

	public function __construct(int $year, int $month, int $day)
	{
		$dt = Parser::fromDay(sprintf('%04d-%02d-%02d', $year, $month, $day));
		if ($dt === null) {
			throw BadFormatException::create();
		}

		$this->setDT($dt);
		$this->day = $this->getDT()->getDay();
		$this->month = $this->getDT()->getMonth();
		$this->year = $this->getDT()->getYear();
	}

	public function __toString(): string
	{
		return $this->getDT()->toHtmlDate();
	}

	public function getName(): string
	{
		return strtolower($this->getDT()->format(Formatter::DAY_NAME));
	}

	public function isWeekend(): bool
	{
		return $this->getDayOfWeek() > 5;
	}

	public function getDay(): int
	{
		return $this->day;
	}

	public function getMonth(): int
	{
		return $this->month;
	}

	public function getYear(): int
	{
		return $this->year;
	}

	public function getDayOfWeek(): int
	{
		return $this->getDT()->getDayOfWeek();
	}

	public function getStartingWeekOffset(): int
	{
		return $this->getDT()->getDayOfWeek() - 1;
	}

	public function getEndingWeekOffset(): int
	{
		return 7 - $this->getDT()->getDayOfWeek();
	}

	public function difference(): Difference
	{
		$start = $this->getDT()->setTime(0, 0);
		return new Difference($start, $start->addDays(1));
	}

	public static function createFromHtml(string $html): self
	{
		$parse = Parser::fromDay($html);

		if ($parse === null) {
			throw BadFormatException::create();
		}

		return new self($parse->getYear(), $parse->getMonth(), $parse->getDay());
	}
}

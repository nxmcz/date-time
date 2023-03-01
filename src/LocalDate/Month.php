<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use DateTimeInterface;
use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils\Formatter;
use Noxem\DateTime\Utils\Parser;

class Month extends DatePart
{
	private int $month;

	private int $year;

	public function __construct(int $year, int $month)
	{
		$this->setDT(DT::createFromParts($year, $month));
		$this->month = $this->getDT()->getMonth();
		$this->year = $this->getDT()->getYear();
	}

	public function __toString(): string
	{
		return $this->getDT()->toHtmlMonth();
	}

	public function getMaximumNumber(): int
	{
		return $this->getLastDayOfMonth()->getDay();
	}

	public function getName(): string
	{
		return strtolower($this->getDT()->format(Formatter::MONTH_NAME));
	}

	public function getYear(): int
	{
		return $this->year;
	}

	public function getMonth(): int
	{
		return $this->month;
	}

    public function getFirstDayOfMonth(): Day
    {
        $dt = $this->getDT();
        return new Day($dt->getYear(), $dt->getMonth(), 1);
    }

	public function getLastDayOfMonth(): Day
	{
		$dt = $this->getDT();
		return new Day($dt->getYear(), $dt->getMonth(), (int) $dt->format(Formatter::LAST_DAY_OF_MONTH));
	}

	public function difference(): Difference\MonthDifference
	{
		return new Difference\MonthDifference($this->getDT());
	}

	public static function createFromHtml(string $html): self
	{
		$parse = Parser::fromMonth($html);

		if ($parse === null) {
			throw BadFormatException::create();
		}

		return new self($parse->getYear(), $parse->getMonth());
	}

	public function getNumber(): int
	{
		return $this->getMonth();
	}

	public static function createFromDT(DT $dt): self
	{
		return self::createFromHtml($dt->toHtmlMonth());
	}

	public function isCurrent(): bool
	{
		$dt = $this->getDT();
		$now = DT::now();
		return $dt->getMonth() === $now->getMonth()
			&& $dt->getYear() === $now->getYear();
	}
}

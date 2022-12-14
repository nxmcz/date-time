<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils\Parser;

class Week extends DatePart
{
	public static function createFromDT(DT $dt): self
	{
		return self::createFromHtml($dt->toHtmlWeek());
	}

	public function __construct(private int $year, private int $week)
	{
		$dt = Parser::fromWeek(sprintf('%04d-W%02d', $year, $week));

		if ($dt === null) {
			throw BadFormatException::create();
		}

		$this->setDT($dt);
		$this->week = $this->getDT()->getWeek();
		$this->year = $this->getDT()->getYear();
	}

	public static function now(): self {
		$dt = DT::now();
		return new self($dt->getYear(), $dt->getWeek());
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
		return new Difference\WeekDifference($this->getDT());
	}

	public static function createFromHtml(string $html): self
	{
		$parse = Parser::fromWeek($html);

		if ($parse === null) {
			throw BadFormatException::create();
		}

		return new self($parse->getYear(), $parse->getWeek());
	}

	public function getName(): string
	{
		return 'W';
	}

	public function isCurrent(): bool
	{
		$dt = $this->getDT();
		$now = DT::now();
		return $dt->getWeek() === $now->getWeek()
			&& $dt->getYear() === $now->getYear();
	}
}

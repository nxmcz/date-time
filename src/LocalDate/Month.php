<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;
use Noxem\DateTime\Utils\Parser;

class Month extends DatePart
{
	public function getMaximumNumber(): int
	{
		return 12;
	}

	public function getNumber(): int
	{
		return $this->dt->getMonth();
	}

	public function getFormat(): string
	{
		return Formatter::MONTH_NAME;
	}

	/**
	 * @params DT|null $dt
	 * @return array<Month>
	 */
	public static function generate(DT $dt = null): array
	{
		$generator = [];
		$dt ??= DT::create();
		$maximum = $dt->getLocalDate()->getMonth()->getMaximumNumber();

		$first = $dt
			->setDate($dt->getYear(), 1, 1)
			->setTime(0, 0);

		for ($i=0; $i<$maximum; $i++) {
			$generator[] = $first->addMonths($i)->getLocalDate()->getMonth();
		}

		return $generator;
	}

	public function getLastDay(): Day
	{
		$dt = $this->getDT();
		return new Day(
			$this
				->getDT()
				->setDate(
					$dt->getYear(),
					$dt->getMonth(),
					(int) $dt->format(Formatter::LAST_DAY_OF_MONTH)
				)
		);
	}

	public function difference(): Difference
	{
		return new Difference(
			$this->getDT(),
			$this->getLastDay()->getDT()
		);
	}

	public function createFromHtml($value): self
	{
		$parse = Parser::fromMonth($value);
		return new self($parse);
	}
}

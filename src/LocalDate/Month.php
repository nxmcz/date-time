<?php declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;

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
	public static function generate(DT $dt = NULL): array
	{
		$generator = array();
		$dt ??= DT::create();
		$maximum = $dt->getLocalDate()->getMonth()->getMaximumNumber();

		$first = $dt
			->setDate($dt->getYear(), 1, 1)
			->setTime(0,0);

		for ($i=0;$i<$maximum;$i++) {
			$generator[] = $first->addMonths($i)->getLocalDate()->getMonth();
		}

		return $generator;
	}

	public function diff(): Difference
	{
		return new Difference($this->getDT(), $this->getDT()->addMonths(1));
	}
}

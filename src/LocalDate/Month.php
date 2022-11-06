<?php declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

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
}

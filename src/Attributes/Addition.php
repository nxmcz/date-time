<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Exception\InvalidArgumentException;
use Noxem\DateTime\Utils;


/**
 * @internal
 */
trait Addition
{
	public function addSeconds(int $seconds): self {
		return $this->makeAdditionRule($seconds, "seconds", TRUE);
	}

	public function subSeconds(int $seconds): self {
		return $this->makeAdditionRule($seconds, "seconds");
	}

	public function addMinutes(int $minutes): self {
		return $this->makeAdditionRule($minutes, "minutes", TRUE);
	}

	public function subMinutes(int $minutes): self {
		return $this->makeAdditionRule($minutes, "minutes");
	}

	public function addHours(int $hours): self {
		return $this->makeAdditionRule($hours, "hours", TRUE);
	}

	public function subHours(int $hours): self {
		return $this->makeAdditionRule($hours, "hours");
	}

	public function addDays(int $days): self {
		return $this->makeAdditionRule($days, "days", TRUE);
	}

	public function subDays(int $days): self {
		return $this->makeAdditionRule($days, "days");
	}

	public function addMonths(int $month): self {
		return $this->makeAdditionRule($month, "months", TRUE);
	}

	public function subMonths(int $month): self {
		return $this->makeAdditionRule($month, "months");
	}

	private function makeAdditionRule(int $variable, string $type, bool $positive = FALSE): self {
		if($variable < 0) {
			throw InvalidArgumentException::create()
				->withMessage("$type must be only positive numbers");
		}

		return $this->modify( ($positive === FALSE ? "-" : NULL) . "$variable $type" );
	}
}

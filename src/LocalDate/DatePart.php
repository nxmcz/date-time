<?php declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\DT;

abstract class DatePart
{
	protected DT $dt;

	public function __construct(
		\DateTimeInterface $dateTimeInterface
	)
	{
		$this->dt = DT::createFromInterface($dateTimeInterface);
	}

	abstract public function getMaximumNumber(): int;

	abstract public function getNumber(): int;

	abstract public function getFormat(): string;

	public function getStartingOffset(): int
	{
		return $this->getNumber() - 1;
	}

	public function getEndingOffset(): int
	{
		return $this->getMaximumNumber() - $this->getNumber();
	}

	public function getName(): string
	{
		return strtolower($this->dt->format($this->getFormat()));
	}

	public function __toString(): string
	{
		return $this->getName();
	}
}

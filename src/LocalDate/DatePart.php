<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use DateTimeInterface;
use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;

abstract class DatePart
{
	protected DT $dt;

	public function __construct(
		?DT $dateTimeInterface
	) {
		$this->dt = $dateTimeInterface ?? DT::now();
	}

	public function __toString(): string
	{
		return $this->getName();
	}

	abstract public function getMaximumNumber(): int;

	abstract public function getNumber(): int;

	abstract public function getFormat(): string;

	abstract public function difference(): Difference;

	abstract public function createFromHtml(string|null|DateTimeInterface $value): self;

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

	public function getDT(): DT
	{
		return $this->dt->setTime(0, 0);
	}
}

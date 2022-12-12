<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;

abstract class DatePart
{
	protected DT $dt;

	abstract public static function createFromDT(DT $dt): self;

	abstract public function getName(): string;

	abstract public function getNumber(): int;

	abstract public function __toString(): string;

	abstract public static function createFromHtml(string $html): self;

	abstract public function difference(): Difference;

	public function getDT(): DT
	{
		return $this->dt;
	}

	public function setDT(DT $dt): self
	{
		$this->dt = $dt->setTime(0, 0);
		return $this;
	}
}

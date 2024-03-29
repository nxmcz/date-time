<?php

declare(strict_types=1);

namespace Noxem\DateTime\LocalDate;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;

class CustomHour extends DatePart
{
	private int $customHours = 1;

	public function __construct(protected string $html)
	{
		$dt = DT::create($html);
		$this->setDT($dt, false);
	}

	public function __toString(): string
	{
		return $this->getDT()->toHumanString();
	}

	public static function createFromDT(DT $dt): self
	{
		return new self($dt->toHumanString());
	}

	public static function createFromHtml(string $html): self
	{
		return new self($html);
	}

	public function getNumber(): int
	{
		return $this->getDT()->getHour();
	}

	public function difference(): Difference
	{
		return new Difference($this->dt, $this->dt->addHours($this->customHours));
	}

	public function getName(): string
	{
		return 'H';
	}

	public function isCurrent(): bool
	{
		return $this->getDT()->areEquals(DT::now());
	}
}

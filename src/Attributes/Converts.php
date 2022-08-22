<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


trait Converts
{
	public function seconds(): int {
		return $this->getTimestamp();
	}

	public function msec(): int {
		return $this->getTimestamp() * 1000;
	}

	public function nano(): float {
		return $this->getTimestamp() * 1e6;
	}

	public function week(): int {
		return (int)$this->format('W');
	}

	public function hour(): int {
		return (int)$this->format('H');
	}

	public function day(): int {
		return (int)$this->format('d');
	}

	public function month(): int {
		return (int)$this->format('m');
	}

	public function year(): int {
		return (int)$this->format('Y');
	}

	public function castToMonthInput(): string {
		return "{$this->year()}-{$this->month()}";
	}
}

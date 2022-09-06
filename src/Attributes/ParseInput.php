<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


/** @internal */
trait ParseInput
{
	public function toDateTimeInput(): string {
		return $this->format("Y-m-d\TH:i:s");
	}

	public function toDateInput(): string {
		return $this->format("Y-m-d");
	}

	public function toMonthInput(): string {
		return $this->format("Y-m");
	}

	public function toWeekInput(): string {
		return "{$this->year()}-W{$this->week()}";
	}

	public function toYearInput(): string {
		return $this->format("Y");
	}
}

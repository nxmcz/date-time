<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;
use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;


class CastToHTML
{
	private DT $value;

	public function __construct(string|int|float|null|DateTimeInterface $value = NULL) {
		$this->value = DT::getOrCreateInstance($value);
	}

	public function toDateTime(): string {
		return $this->value->format("Y-m-d\TH:i:s");
	}

	public function toDate(): string {
		return $this->value->format("Y-m-d");
	}

	public function toMonth(): string {
		return $this->value->format("Y-m");
	}

	public function toWeek(): string {
		return "{$this->value->format("Y")}-W{$this->value->format("W")}";
	}

	public function toYear(): string {
		return $this->value->format("Y");
	}
}

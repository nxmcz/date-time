<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;

use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;


class HtmlInputConversion
{
	private DT $value;

	public function __construct($value) {
		$this->value = DT::getOrCreateInstance($value);
	}

	public function toDateTime(): string {
		return $this->value->format("Y-m-d\TH:i:s");
	}

	public function toUTC(): string {
		return $this->value->format(Formatter::UTC);
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

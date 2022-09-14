<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


use Noxem\DateTime\Utils\Formatter;

trait DateConversion
{
	public function seconds(): int {
		return $this->getTimestamp();
	}

	public function msec(): int {
		return $this->getTimestamp() * 1000;
	}

	public function millis(): int {
		return (int)$this->format(Formatter::MILLIS);
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
}

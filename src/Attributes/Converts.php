<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;


trait Converts
{
	public function msec(): int {
		return $this->getTimestamp() * 1000;
	}

	public function nano(): float {
		return $this->getTimestamp() * 1e6;
	}
}

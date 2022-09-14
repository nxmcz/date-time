<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;


trait TimeConversion
{
	abstract public function createSecondsForConversion(): int;

	public function seconds(): int
	{
		return $this->createSecondsForConversion();
	}

	public function msec(): int
	{
		return $this->seconds() * 1000;
	}

	public function minutes(): float
	{
		return $this->seconds() / 60;
	}

	public function hours(): float
	{
		return $this->seconds() / 3600;
	}
}

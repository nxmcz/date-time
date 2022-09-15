<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;


trait TimeConversion
{
	abstract public function createBaseForTimeConversion(): int;

	public function getSeconds(): int
	{
		return $this->createBaseForTimeConversion();
	}

	public function getMillis(): int
	{
		return $this->getSeconds() * 1000;
	}

	public function getMinutes(): float
	{
		return $this->getSeconds() / 60;
	}

	public function getHours(): float
	{
		return $this->getSeconds() / 3600;
	}
}

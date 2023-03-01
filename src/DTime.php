<?php declare(strict_types=1);

namespace Noxem\DateTime;

use DateTimeImmutable as NativeDateTimeImmutable;
use DateTimeInterface;
use Noxem\DateTime\Attributes;
use Noxem\DateTime\Utils;


class DTime
{
	use Attributes\TimeConversion;

	public function __construct(
		private int $seconds
	) {
	}

	public static function create(int $seconds): self {
		return new self($seconds);
	}

	public function createBaseForTimeConversion(): int
	{
		return $this->seconds;
	}

    public function isValid(): bool {
        return $this->seconds > 0;
    }
}

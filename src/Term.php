<?php declare(strict_types = 1);

namespace Noxem\DateTime;


class Term
{
	public function __construct(
		private DateTimeImmutable $dateTimeImmutable
	) {
	}

	public function day(): int {
		return (int)$this->dateTimeImmutable->format('d');
	}

	public function month(): int {
		return (int)$this->dateTimeImmutable->format('m');
	}

	public function year(): int {
		return (int)$this->dateTimeImmutable->format('Y');
	}
}

<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Exception\InvalidArgumentException;
use Noxem\DateTime\Utils;


/**
 * @internal
 */
trait Casting
{
	public function toLocalHumanString(): string {
		return $this->getLocal()->format(Utils\Formatter::TIMESTAMPS);
	}

	public function toHumanString(): string {
		return $this->format(Utils\Formatter::TIMESTAMPS);
	}

	public function toIso8601ZuluString(): string {
		return $this->setTimezone(new \DateTimeZone("UTC"))->format(Utils\Formatter::UTC);
	}

	public function toLocalDateTimeString(): string {
		return $this->getLocal()->format(Utils\Formatter::LOCAL);
	}

	public function toDateTimeString(): string {
		return $this->format(Utils\Formatter::LOCAL);
	}

	public function toW3cString(): string {
		return $this->format(DATE_W3C);
	}

	public function toRssString(): string {
		return $this->format(DATE_RSS);
	}

	public function toAtomString(): string {
		return $this->format(DATE_ATOM);
	}

	public function toHtmlDate(): string {
		return $this->format("Y-m-d");
	}

	public function toHtmlMonth(): string {
		return $this->format("Y-m");
	}

	public function toHtmlWeek(): string {
		return "{$this->format("Y")}-W{$this->format("W")}";
	}

	public function toHtmlYear(): string {
		return $this->format("Y");
	}

	public function getLocal(): self {
		return $this->setTimezone(new \DateTimeZone(date_default_timezone_get()));
	}
}

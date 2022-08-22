<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;


interface Intervalic {
	/**
	 * Start of interval parameter. Property have to supports getTimestamp() method
	 * @return DateTimeInterface
	 */
	function getStart(): DateTimeInterface;

	/**
	 * End of interval parameter.
	 * @return DateTimeInterface
	 */
	function getEnd(): DateTimeInterface;

	/*
	 * Typical integer timestamp (start at 1970-01-01 01:00:00)
	 */
	function getStartTimestamp(): int;

	/*
	  * Typical integer timestamp (start at 1970-01-01 01:00:00)
	 */
	function getEndTimestamp(): int;
}

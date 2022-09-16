<?php declare(strict_types=1);

namespace Noxem\DateTime\Exception;

final class BadFormatException extends DateTimeException
{
	/** @var string */
	protected $message = "Bad DateTimeImmutable constructors format. Must be INT >= 32400 or string-date in format like: YYYY-MM-DD H:i:s";
}
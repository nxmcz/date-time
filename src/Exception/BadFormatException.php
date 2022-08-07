<?php declare(strict_types=1);

namespace Noxem\DateTime\Exception;


final class BadFormatException extends \InvalidArgumentException
{
	public static function create(): self
	{
		return throw new self("Bad DateTimeImmutable constructors format. Must be INT >= 32400 or string-date in format like: YYYY-MM-DD H:i:s");
	}
}
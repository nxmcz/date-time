<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;


class Formatter
{
	public const DATE = 'Y-m-d';
	public const TIME = 'H:i';
	public const TIMES = 'H:i:s';
	public const TIMESTAMP = 'Y-m-d H:i';
	public const TIMESTAMPS = 'Y-m-d H:i:s';
	public const TIMESTAMP_MILLIS = 'Y-m-d H:i:s.u';
	public const MILLIS = 'u';
	public const ISO8601 = 'Y-m-d\TH:i:sP';
	public const UTC = 'Y-m-d\TH:i:s\Z';
}

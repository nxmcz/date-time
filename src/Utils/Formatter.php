<?php declare(strict_types=1);

namespace Noxem\DateTime\Utils;


use Nette\Utils\Reflection;
use ReflectionClassConstant;

class Formatter
{
	public const DATE = 'Y-m-d';
	public const TIME = 'H:i';
	public const TIMES = 'H:i:s';
	public const SLASHES = 'Y/m/d';
	public const SLASHES_TIME = 'Y/m/d H:i';
	public const SLASHES_TIMES = 'Y/m/d H:i:s';
	public const CZECH = 'd.m.Y H:i:s';
	public const TIMESTAMP = 'Y-m-d H:i';
	public const TIMESTAMPS = 'Y-m-d H:i:s';
	public const TIMESTAMP_MILLIS = 'Y-m-d H:i:s.u';
	public const MILLIS = 'u';
	public const C = 'c';
	public const ATOM = 'Y-m-d\TH:i:sP';
	public const UTC = 'Y-m-d\TH:i:s\Z';
	public const UTC_WT = 'Y-m-d\TH:i:s\ZP';
}

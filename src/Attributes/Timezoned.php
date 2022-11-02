<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

use DateTimeZone;

trait Timezoned
{
	public function getTimezone(): DateTimeZone
	{
		if ($this->dateTimeZone === NULL) {
			$this->dateTimeZone = new DateTimeZone(date_default_timezone_get());
		}

		return $this->dateTimeZone;
	}
}

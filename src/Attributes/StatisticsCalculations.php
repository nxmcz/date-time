<?php

declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

/**
 * @internal
 */
trait StatisticsCalculations
{
	public static function min(array $arrayOfDt): ?self
	{
		$min = null;

		foreach ($arrayOfDt as $dt) {
			if ($dt instanceof self) {
				if ($min === null || $dt < $min) {
					$min = $dt;
				}
			}
		}

		return $min;
	}

	public static function max(array $arrayOfDt): ?self
	{
		$min = null;

		foreach ($arrayOfDt as $dt) {
			if ($dt instanceof self) {
				if ($min === null || $dt > $min) {
					$min = $dt;
				}
			}
		}

		return $min;
	}
}

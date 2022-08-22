<?php declare(strict_types = 1);

namespace Noxem\DateTime;

use DateTimeInterface as AnyDT;


class Overlapping
{
	/**
	 * EXAMPLE												|===========|
	 *
	 * AFTER										======	|			|							FALSE
	 * START TOUCHING								 =======|			|							FALSE
	 * START INSIDE										====|===		|							TRUE
	 * INSIDE START TOUCHING								|===========|=======					TRUE
	 * ENCLOSING START TOUCHING								|======		|							TRUE
	 * ENCLOSING											|	=====	|							TRUE
	 * ENCLOSING END TOUCHING								|	    ====|							TRUE
	 * EXACT MATCH											|===========|							TRUE
	 * INSIDE											====|===========|======						TRUE
	 * INSIDE END TOUCHING								====|===========|							TRUE
	 * END INSIDE											|		====|=============				TRUE
	 * END TOUCHING											|			|========					FALSE
	 * BEFORE												|			|   ===========				FALSE
	 *
	 */
	public static function withTouching(
		AnyDT $suspectFrom,
		AnyDT $suspectTo,
		AnyDT $targetFrom,
		AnyDT $targetTo,
	): bool {
		$suspectFrom = $suspectFrom->getTimestamp();
		$suspectTo = $suspectTo->getTimestamp();
		$targetFrom = $targetFrom->getTimestamp();
		$targetTo = $targetTo->getTimestamp();

		return (($suspectFrom >= $targetFrom) && ($suspectTo < $targetTo))
			|| (($suspectFrom >= $targetFrom) && ($suspectFrom < $targetTo))
			|| (($suspectFrom < $targetFrom) && ($suspectTo >= $targetTo))
			|| (($suspectTo > $targetFrom) && ($suspectTo < $targetTo));
	}
}

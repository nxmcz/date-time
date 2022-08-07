<?php declare(strict_types = 1);

namespace Noxem\DateTime\Attributes;

use DateTimeInterface;


trait Overlapping
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
	public static function isOverlap(
		DateTimeInterface $suspectFrom,
		DateTimeInterface $suspectTo,
		DateTimeInterface $targetFrom,
		DateTimeInterface $targetTo,
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

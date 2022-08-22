<?php

namespace Tests;

use Noxem\DateTime\Overlapping;
use Noxem\DateTime\DT;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


test('isOverlap', function() {
	/*
	 * EXAMPLE												|███████████|
	 *
	 * AFTER										██████	|			|									FALSE
	 * START TOUCHING								 ███████|			|									FALSE
	 * START INSIDE										████|██			|									TRUE
	 * INSIDE START TOUCHING								|███████████|███████							TRUE
	 * ENCLOSING START TOUCHING								|██████		|									TRUE
	 * ENCLOSING											|	█████	|									TRUE
	 * ENCLOSING END TOUCHING								|	    ████|									TRUE
	 * EXACT MATCH											|███████████|									TRUE
	 * INSIDE											████|███████████|██████								TRUE
	 * INSIDE END TOUCHING								████|███████████|									TRUE
	 * END INSIDE											|		████|█████████████						TRUE
	 * END TOUCHING											|			|████████							FALSE
	 * BEFORE												|			|	███████████						FALSE
	 *
	 */

	# AFTER
	Assert::false(
		Overlapping::withTouching(
			DT::create('2021-05-06 09:00:00'),
			DT::create('2021-05-06 09:59:59'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# START TOUCHING
	Assert::false(
		Overlapping::withTouching(
			DT::create('2021-05-06 09:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# START INSIDE
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 09:00:00'),
			DT::create('2021-05-06 12:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# INSIDE START TOUCHING
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 15:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# ENCLOSING START TOUCHING
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 12:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# ENCLOSING
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 11:00:00'),
			DT::create('2021-05-06 12:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# ENCLOSING END TOUCHING
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 11:00:00'),
			DT::create('2021-05-06 13:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# EXACT MATCH
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# INSIDE
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 09:00:00'),
			DT::create('2021-05-06 14:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# INSIDE END TOUCHING
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 09:00:00'),
			DT::create('2021-05-06 13:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# END INSIDE
	Assert::true(
		Overlapping::withTouching(
			DT::create('2021-05-06 11:00:00'),
			DT::create('2021-05-06 14:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# END TOUCHING
	Assert::false(
		Overlapping::withTouching(
			DT::create('2021-05-06 13:00:00'),
			DT::create('2021-05-06 14:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);

	# BEFORE
	Assert::false(
		Overlapping::withTouching(
			DT::create('2021-05-06 13:00:01'),
			DT::create('2021-05-06 14:00:00'),
			DT::create('2021-05-06 10:00:00'),
			DT::create('2021-05-06 13:00:00')
		)
	);
});
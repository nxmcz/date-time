<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateTime;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Overlapping;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class OverlappingTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerIsOverlap
	 */
	public function testIsOverlap(bool $isOverlap, array $times)
	{
		Assert::same($isOverlap, Overlapping::withTouching(...array_map(fn($v) => DT::create($v), $times)));
	}

	public function providerIsOverlap(): array
	{
		return [
			[ # AFTER
				FALSE, [
				'2021-05-06 09:00:00',
				'2021-05-06 09:59:59',
				'2021-05-06 10:00:00',
				'2021-05-06 13:00:00'
			]
			], [ # START TOUCHING
				FALSE, [
					'2021-05-06 09:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # START INSIDE
				TRUE, [
					'2021-05-06 09:00:00',
					'2021-05-06 12:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # INSIDE START TOUCHING
				TRUE, [
					'2021-05-06 10:00:00',
					'2021-05-06 15:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # ENCLOSING START TOUCHING
				TRUE, [
					'2021-05-06 10:00:00',
					'2021-05-06 12:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # ENCLOSING
				TRUE, [
					'2021-05-06 11:00:00',
					'2021-05-06 12:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # ENCLOSING END TOUCHING
				TRUE, [
					'2021-05-06 11:00:00',
					'2021-05-06 13:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # EXACT MATCH
				TRUE, [
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # INSIDE
				TRUE, [
					'2021-05-06 9:00:00',
					'2021-05-06 14:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # INSIDE END TOUCHING
				TRUE, [
					'2021-05-06 9:00:00',
					'2021-05-06 13:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # END INSIDE
				TRUE, [
					'2021-05-06 11:00:00',
					'2021-05-06 14:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # END TOUCHING
				FALSE, [
					'2021-05-06 13:00:00',
					'2021-05-06 14:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			], [ # BEFORE
				FALSE, [
					'2021-05-06 13:00:01',
					'2021-05-06 14:00:00',
					'2021-05-06 10:00:00',
					'2021-05-06 13:00:00'
				]
			]
		];
	}
}

$test = new OverlappingTest();
$test->run();
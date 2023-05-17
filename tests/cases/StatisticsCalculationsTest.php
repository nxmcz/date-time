<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Noxem\DateTime\DT;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class StatisticsCalculationsTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerMinData
	 */
	public function testMin(?string $minDate, array $dates): void
	{
		Assert::same(
			DT::create($minDate)->getTimestamp(),
			DT::min($dates)->getTimestamp()
		);
	}

	public function providerMinData(): Generator
	{
		yield ['2023-01-04 00:00:09', [DT::create('2023-01-04 00:00:10'), DT::create('2023-01-04 00:00:11'), DT::create('2023-01-04 00:00:09')]];
		yield ['2000-01-04 00:00:00', [DT::create('2021-01-04 00:00:00'), DT::create('2000-01-04 00:00:00'), DT::create('2023-01-04 00:00:00')]];
	}

	/**
	 * @dataProvider providerMaxData
	 */
	public function testMax(?string $maxDate, array $dates): void
	{
		Assert::same(
			DT::create($maxDate)->getTimestamp(),
			DT::max($dates)->getTimestamp()
		);
	}

	public function providerMaxData(): Generator
	{
		yield ['2023-01-04 00:00:11', [DT::create('2023-01-04 00:00:10'), DT::create('2023-01-04 00:00:11'), DT::create('2023-01-04 00:00:09')]];
		yield ['2023-01-04 00:00:00', [DT::create('2021-01-04 00:00:00'), DT::create('2000-01-04 00:00:00'), DT::create('2023-01-04 00:00:00')]];
	}
}

$test = new StatisticsCalculationsTest();
$test->run();

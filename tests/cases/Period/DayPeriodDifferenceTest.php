<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\Period;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class DayPeriodDifferenceTest extends AbstractTestCase
{
	/**
	 * @dataProvider providePeriodCases
	 */
	public function testPeriodCases(int $dayQuantity, string $from, string $to, string $firstStart, string $firstEnd, string $lastStart, string $lastEnd, Period $period): void
	{
		$periodDifference = new Difference\PeriodDifference(DT::create($from), DT::create($to), $period);
		$arr = iterator_to_array($periodDifference->getIterator());

		$numOfElements = count($arr);

		Assert::same($arr[0]->getStart()->toHumanString(), $firstStart);
		Assert::same($arr[0]->getEnd()->toHumanString(), $firstEnd);
		Assert::same($arr[$numOfElements-1]->getStart()->toHumanString(), $lastStart);
		Assert::same($arr[$numOfElements-1]->getEnd()->toHumanString(), $lastEnd);
		Assert::equal($numOfElements, $dayQuantity);
	}

	public function providePeriodCases(): \Generator
	{
		yield [1, '2022-10-02 09:33:21', '2022-10-02', '2022-10-02 00:00:00', '2022-10-03 00:00:00', '2022-10-02 00:00:00', '2022-10-03 00:00:00', Period::DAY];
		yield [5, '2022-10-02 09:33:21', '2022-10-06', '2022-10-02 00:00:00', '2022-10-03 00:00:00', '2022-10-06 00:00:00', '2022-10-07 00:00:00', Period::DAY];

		yield [1, '2022-12-05 09:33:21', '2022-12-11 23:59:59', '2022-12-05 00:00:00', '2022-12-12 00:00:00', '2022-12-05 00:00:00', '2022-12-12 00:00:00', Period::WEEK];
		yield [2, '2022-12-05 09:33:21', '2022-12-12 00:00:00', '2022-12-05 00:00:00', '2022-12-12 00:00:00', '2022-12-12 00:00:00', '2022-12-19 00:00:00', Period::WEEK];

		yield [1, '2022-12-01 00:00:00', '2022-12-31 23:59:59', '2022-12-01 00:00:00', '2023-01-01 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', Period::MONTH];
		yield [1, '2022-12-05 09:33:21', '2022-12-12 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', Period::MONTH];
	}
}

$test = new DayPeriodDifferenceTest();
$test->run();

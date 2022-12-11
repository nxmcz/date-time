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
class PeriodDifferenceTest extends AbstractTestCase
{
	/**
	 * @dataProvider providePeriodCases
	 */
	public function testPeriodCases(int $dayQuantity, string $from, string $to, string $firstStart, string $firstEnd, string $lastStart, string $lastEnd, Period $period, int $pureDifferenceInSeconds): void
	{
		$periodDifference = new Difference\PeriodDifference(DT::create($from), DT::create($to), $period);
		$arr = iterator_to_array($periodDifference->getIterator());

		$numOfElements = count($arr);
		Assert::equal($numOfElements, $dayQuantity);
		Assert::equal($periodDifference->getSeconds(), $pureDifferenceInSeconds);
		Assert::same($arr[0]->getStart()->toHumanString(), $firstStart);
		Assert::same($arr[0]->getEnd()->toHumanString(), $firstEnd);
		Assert::same($arr[$numOfElements-1]->getStart()->toHumanString(), $lastStart);
		Assert::same($arr[$numOfElements-1]->getEnd()->toHumanString(), $lastEnd);
	}

	public function providePeriodCases(): \Generator
	{
		yield [1, '2022-10-02 09:33:21', '2022-10-02', '2022-10-02 00:00:00', '2022-10-03 00:00:00', '2022-10-02 00:00:00', '2022-10-03 00:00:00', Period::DAY, 86400];
		yield [5, '2022-10-02 09:33:21', '2022-10-06', '2022-10-02 00:00:00', '2022-10-03 00:00:00', '2022-10-06 00:00:00', '2022-10-07 00:00:00', Period::DAY, 5*86400];
		yield [31, '2022-10-01 09:33:21', '2022-10-31', '2022-10-01 00:00:00', '2022-10-02 00:00:00', '2022-10-31 00:00:00', '2022-11-01 00:00:00', Period::DAY, 31*86400];

		yield [1, '2022-12-05 09:33:21', '2022-12-11 23:59:59', '2022-12-05 00:00:00', '2022-12-12 00:00:00', '2022-12-05 00:00:00', '2022-12-12 00:00:00', Period::WEEK, 7*86400];
		yield [2, '2022-12-05 09:33:21', '2022-12-12 00:00:00', '2022-12-05 00:00:00', '2022-12-12 00:00:00', '2022-12-12 00:00:00', '2022-12-19 00:00:00', Period::WEEK, 14*86400];
		yield [3, '2022-07-04 09:33:21', '2022-07-19 00:00:00', '2022-07-04 00:00:00', '2022-07-11 00:00:00', '2022-07-18 00:00:00', '2022-07-25 00:00:00', Period::WEEK, 21*86400];

		yield [1, '2022-12-01 00:00:00', '2022-12-31 23:59:59', '2022-12-01 00:00:00', '2023-01-01 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', Period::MONTH, 31*86400];
		yield [1, '2022-12-05 09:33:21', '2022-12-12 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', Period::MONTH, 31*86400];
		yield [7, '2022-12-01 00:00:00', '2023-06-01 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', '2023-06-01 00:00:00', '2023-07-01 00:00:00', Period::MONTH, (31+28+31+30+31+30+31)*86400];
		yield [7, '2022-12-05 09:33:21', '2023-06-01 00:00:00', '2022-12-01 00:00:00', '2023-01-01 00:00:00', '2023-06-01 00:00:00', '2023-07-01 00:00:00', Period::MONTH, (31+28+31+30+31+30+31)*86400];
		yield [7, '2019-12-01 00:00:00', '2020-06-01 00:00:00', '2019-12-01 00:00:00', '2020-01-01 00:00:00', '2020-06-01 00:00:00', '2020-07-01 00:00:00', Period::MONTH, (31+29+31+30+31+30+31)*86400];
	}
}

$test = new PeriodDifferenceTest();
$test->run();

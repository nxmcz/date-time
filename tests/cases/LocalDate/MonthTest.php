<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class MonthTest extends AbstractTestCase
{
	/**
	 * @dataProvider provideValidCases
	 */
	public function testValidCases(string $htmlFormat, int $year, int $month, int $maximumDays, string $lastDayString, string $name): void
	{
		$case = LocalDate\Month::createFromHtml($htmlFormat);
		Assert::same($year, $case->getYear());
		Assert::same($month, $case->getMonth());
		Assert::same($maximumDays, $case->getMaximumNumber());
		Assert::same($name, $case->getName());
		Assert::true(DT::create($lastDayString)->areEquals($case->getLastDayOfMonth()->getDT()));
		Assert::same((string) $case, $htmlFormat);
	}

	public function provideValidCases(): \Generator
	{
		yield ['2022-01', 2022, 1, 31, '2022-01-31', 'january'];
		yield ['2022-11', 2022, 11, 30, '2022-11-30', 'november'];
		yield ['2020-02', 2020, 2, 29, '2020-02-29', 'february'];
		yield ['2021-02', 2021, 2, 28, '2021-02-28', 'february'];
	}

	/**
	 * @dataProvider provideDuration
	 */
	public function testDuration(int $year, int $month, string $from, string $to): void
	{
		$case = new LocalDate\Month($year, $month);

		Assert::same($case->difference()->getStart()->toHumanString(), $from);
		Assert::same($case->difference()->getEnd()->toHumanString(), $to);
	}

	public function provideDuration(): \Generator
	{
		yield [2022, 11, '2022-11-01 00:00:00', '2022-12-01 00:00:00'];
		yield [2022, 12, '2022-12-01 00:00:00', '2023-01-01 00:00:00'];
	}
}

$test = new MonthTest();
$test->run();

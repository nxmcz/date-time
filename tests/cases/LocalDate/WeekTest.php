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
class WeekTest extends AbstractTestCase
{
	/**
	 * @dataProvider provideDuration
	 */
	public function testDuration(int $year, int $week, string $from, string $to): void
	{
		$case = new LocalDate\Week($year, $week);

		Assert::same($case->difference()->getStart()->toHumanString(), $from);
		Assert::same($case->difference()->getEnd()->toHumanString(), $to);
	}

	public function provideDuration(): \Generator
	{
		yield [2022, 50, '2022-12-12 00:00:00', '2022-12-19 00:00:00'];
		yield [2022, 49, '2022-12-05 00:00:00', '2022-12-12 00:00:00'];
	}

	public function testIsCurrent(): void
	{
		$now = DT::now();
		$previous = $now->modifyDays(-100);

		$caseNow = new LocalDate\Week($now->getYear(), $now->getWeek(),);
		$casePrevious = new LocalDate\Week($previous->getYear(), $previous->getWeek());

		Assert::true($caseNow->isCurrent());
		Assert::false($casePrevious->isCurrent());
	}
}

$test = new WeekTest();
$test->run();

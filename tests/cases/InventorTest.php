<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\DT as DateTime;
use Noxem\DateTime\Difference;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\LocalDate\DatePart;
use Noxem\DateTime\LocalDate\Day;
use Noxem\DateTime\LocalDate\Week;
use Noxem\DateTime\Period;
use Noxem\DateTime\Utils\Inventor;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class InventorTest extends AbstractTestCase
{
	/**
	 * @dataProvider provideGenerateIteratorResults
	 */
	public function testGenerateIteratorResults(
		Period $period,
		DatePart $from,
		DatePart $to,
		int $expectedQuantityInGenerator,
		string $firstStart,
		string $firstEnd,
		string $lastStart,
		string $lastEnd,
		int $differenceInSeconds
	): void
	{
		$generator = new Inventor($from, $to, $period);

		Assert::equal($expectedQuantityInGenerator, $generator->count());
		Assert::same((string)$generator->first(), $firstStart);
		//Assert::same((string)$generator->first(), $firstEnd);
		Assert::same((string)$generator->last(), $lastStart);
		//Assert::same((string)$generator->last(), $lastEnd);
		Assert::same($differenceInSeconds, $generator->difference()->getSeconds());
	}

	public function provideGenerateIteratorResults(): \Generator
	{
		yield [
			Period::DAY,
			new Day(2022, 10, 10),
			new Day(2022, 10, 10),
			1,
			'2022-10-10 00:00:00',
			'2022-10-11 00:00:00',
			'2022-10-10 00:00:00',
			'2022-10-11 00:00:00',
			86400
		];

		yield [
			Period::DAY,
			new Day(2022, 10, 10),
			new Day(2022, 10, 11),
			2,
			'2022-10-10 00:00:00',
			'2022-10-11 00:00:00',
			'2022-10-11 00:00:00',
			'2022-10-12 00:00:00',
			2*86400
		];

		yield [
			Period::DAY,
			new Day(2022, 10, 10),
			new Day(2022, 10, 20),
			11,
			'2022-10-10 00:00:00',
			'2022-10-11 00:00:00',
			'2022-10-20 00:00:00',
			'2022-10-21 00:00:00',
			11*86400
		];

		yield [
			Period::WEEK,
			new Week(2022, 50),
			new Week(2022, 50),
			1,
			'2022-12-12 00:00:00',
			'2022-12-19 00:00:00',
			'2022-12-12 00:00:00',
			'2022-12-19 00:00:00',
			7*86400
		];

		yield [
			Period::WEEK,
			new Week(2022, 50),
			new Week(2022, 51),
			2,
			'2022-12-12 00:00:00',
			'2022-12-19 00:00:00',
			'2022-12-19 00:00:00',
			'2022-12-26 00:00:00',
			14*86400
		];
	}

	public function testThrowsWhenToParameterGreater(): void
	{
		Assert::throws(
			fn() => Inventor::day(new Day(2022, 8, 3), new Day(2022, 8, 2)),
			BadFormatException::class
		);
	}
}

$test = new InventorTest();
$test->run();
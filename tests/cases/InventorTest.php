<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\DT as DateTime;
use Noxem\DateTime\Difference;
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
		$generatorArray = iterator_to_array($generator);
		$quantityInGenerator = count($generatorArray);

		Assert::equal($expectedQuantityInGenerator, $quantityInGenerator);
		Assert::same((string)$generatorArray[0]->difference()->getStart(), $firstStart);
		Assert::same((string)$generatorArray[0]->difference()->getEnd(), $firstEnd);

		Assert::same((string)$generatorArray[$quantityInGenerator-1]->difference()->getStart(), $lastStart);
		Assert::same((string)$generatorArray[$quantityInGenerator-1]->difference()->getEnd(), $lastEnd);

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
}

$test = new InventorTest();
$test->run();
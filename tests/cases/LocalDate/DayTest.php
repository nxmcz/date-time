<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\LocalDate;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class DayTest extends AbstractTestCase
{
	/**
	 * @dataProvider provideValidCases
	 */
	public function testValidCases(string $htmlFormat, int $year, int $month, int $day, int $startOffset, int $endOffset, int $dayOfWeek, string $dayName, bool $isWeekend): void
	{
		$case = LocalDate\Day::createFromHtml($htmlFormat);
		Assert::same($year, $case->getYear());
		Assert::same($month, $case->getMonth());
		Assert::same($day, $case->getDay());

		Assert::same($dayOfWeek, $case->getDayOfWeek());
		Assert::same($startOffset, $case->getStartingWeekOffset());
		Assert::same($endOffset, $case->getEndingWeekOffset());

		Assert::same($dayName, $case->getName());
		Assert::same($isWeekend, $case->isWeekend());
		Assert::same((string) $case, $htmlFormat);
	}

	public function provideValidCases(): \Generator
	{
		yield['2022-11-05', 2022, 11, 5, 5, 1, 6, 'saturday', true];
		yield['2022-11-06', 2022, 11, 6, 6, 0, 7, 'sunday', true];
		yield['2022-11-07', 2022, 11, 7, 0, 6, 1, 'monday', false];
		yield['2022-11-08', 2022, 11, 8, 1, 5, 2, 'tuesday', false];
		yield['2022-11-09', 2022, 11, 9, 2, 4, 3, 'wednesday', false];
		yield['2022-11-10', 2022, 11, 10, 3, 3, 4, 'thursday', false];
		yield['2022-11-11', 2022, 11, 11, 4, 2, 5, 'friday', false];
	}
}

$test = new DayTest();
$test->run();

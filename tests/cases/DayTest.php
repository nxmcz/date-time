<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class DayTest extends AbstractTestCase
{
	/**
	 * @dataProvider times
	 */
	public function testDays(string $date, int $startOffset, int $endOffset, int $dayOfWeek, string $dayName): void
	{
		$d = LocalDate::createFromString($date)->getDay();
		Assert::same($startOffset, $d->getStartingWeekOffset());
		Assert::same($endOffset, $d->getEndingWeekOffset());
		Assert::same($dayOfWeek, $d->getDayOfWeek());
		Assert::same($dayName, $d->getName());
		Assert::same(30, $d->getMaximumNumber());

		$generator = $d::generate( DT::create("2022-11-07") );
		Assert::type('array', $generator);
		Assert::same(30, count($generator));
		Assert::same('2022-11-01 00:00:00', (string)$generator[0]->getDT());
		Assert::same('2022-11-30 00:00:00', (string)$generator[29]->getDT());
	}



	public function times(): array
	{
		return [
			[
				'2022-11-05', 5, 1, 6, 'saturday',
				'2022-11-06', 6, 0, 7, 'sunday',
				'2022-11-07', 0, 6, 1, 'monday',
				'2022-11-08', 1, 5, 2, 'tuesday',
				'2022-11-09', 2, 4, 3, 'wednesday',
				'2022-11-10', 3, 3, 4, 'thursday',
				'2022-11-11', 4, 2, 5, 'friday'
			],
		];
	}
}

$test = new DayTest();
$test->run();

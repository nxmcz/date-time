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
class MonthTest extends AbstractTestCase
{
	/**
	 * @dataProvider times
	 */
	public function testDays(string $date, int $startOffset, int $endOffset, int $number, string $name): void
	{
		$d = LocalDate::createFromString($date)->getMonth();
		Assert::same($startOffset, $d->getStartingOffset());
		Assert::same($endOffset, $d->getEndingOffset());
		Assert::same($number, $d->getNumber());
		Assert::same($name, $d->getName());

		$generator = $d::generate( DT::create("2022-11-07") );
		Assert::type('array', $generator);
		Assert::same(12, count($generator));
		Assert::same('2022-01-01 00:00:00', (string)$generator[0]->getDT());
		Assert::same('2022-12-01 00:00:00', (string)$generator[11]->getDT());
	}

	public function times(): array
	{
		return [
			[
				'2022-01-05', 0, 11, 1, 'january',
				'2022-02-06', 1, 10, 2, 'february',
				'2022-03-07', 2, 9, 3, 'march',
				'2022-04-08', 3, 8, 4, 'april',
				'2022-05-09', 4, 7, 5, 'may',
				'2022-06-10', 5, 6, 6, 'june',
				'2022-07-11', 6, 5, 7, 'july',
				'2022-08-08', 7, 4, 8, 'august',
				'2022-09-09', 8, 3, 9, 'september',
				'2022-10-10', 9, 2, 10, 'october',
				'2022-11-11', 10, 1, 11, 'november',
				'2022-12-11', 11, 0, 12, 'december',
			],
		];
	}
}

$test = new MonthTest();
$test->run();

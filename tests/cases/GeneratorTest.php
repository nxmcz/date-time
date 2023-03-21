<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Nette\Utils\Arrays;
use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class GeneratorTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerData
	 */
	public function testMonth(int $monthNumber, int $yearNumber, string $firstDay, string $lastDay): void
	{
		$generator = \Noxem\DateTime\Utils\Generator::month($monthNumber, $yearNumber);
        $array = iterator_to_array($generator);

        Assert::same( (string)Arrays::first($array), $firstDay );
        Assert::same( (string)Arrays::last($array), $lastDay );
        Assert::type( LocalDate::class, Arrays::first($array));
	}

	public function providerData(): Generator
	{
        yield [1, 2023, '2023-01-01', '2023-01-31'];
		yield [2, 2023, '2023-02-01', '2023-02-28'];
        yield [2, 2024, '2024-02-01', '2024-02-29'];
	}
}

$test = new GeneratorTest();
$test->run();
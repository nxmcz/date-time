<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\LocalDate;
use Noxem\DateTime\Utils\Generator;
use Noxem\DateTime\Utils\Helpers;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class GeneratorTest extends AbstractTestCase
{
	public function testWeek(): void
	{
		$w = Generator::week( DT::create("2022-11-07") );

		Assert::same(7, count($w));
		Assert::same('2022-11-07 00:00:00', (string)$w[0]->getDT());
		Assert::same('2022-11-13 00:00:00', (string)$w[6]->getDT());

		$w = Generator::week();
		Assert::same(7, count($w));
		Assert::same('monday', (string)$w[0]);
		Assert::same('sunday', (string)$w[6]);
	}
}

$test = new GeneratorTest();
$test->run();

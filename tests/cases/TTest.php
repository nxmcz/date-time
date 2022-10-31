<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\T;
use Noxem\DateTime\Utils\Parser;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class TTest extends AbstractTestCase
{
	/**
	 * @dataProvider times
	 */
	public function testResults(bool $exception, mixed $suspect, array $results): void
	{
		if($exception) {
			Assert::exception(fn() => T::create($suspect), BadFormatException::class);
		} else {
			$t = T::create($suspect);

			Assert::same(
				$results,
				[
					$t->getHour(),
					$t->getMinute(),
					$t->getSecond(),
					$t->getMillis()
				]
			);
		}
	}

	public function times(): array
	{
		return [
			[FALSE, '12:34:56', [12,34,56,0]],
			[FALSE, '00:34:56', [0,34,56,0]],
			[FALSE, '12:00:56', [12,0,56,0]],
			[FALSE, '12:34:00', [12,34,0,0]],
			[FALSE, '00:00:00', [0,0,0,0]],
		];
	}
}

$test = new TTest();
$test->run();
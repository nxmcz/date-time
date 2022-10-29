<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Validator;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ValidatorTest extends AbstractTestCase
{
	/**
	 * @dataProvider results
	 */
	public function testBasic(bool $result, string $date): void
	{
		Assert::same($result, Validator::isWeekend( DT::create($date) ));
	}

	public function results(): array {
		return [
			[TRUE, '2022-10-29 00:00:00'],
			[TRUE, '2022-10-30 00:00:00'],
			[TRUE, '2021-03-20 00:00:00'],
			[FALSE, '2022-10-31 00:00:00'],
			[FALSE, '2022-10-28 00:00:00'],
			[FALSE, '2022-10-27 00:00:00'],
			[FALSE, '2022-10-24 00:00:00'],

			[FALSE, '17.09.2021 23:59:59'],
			[TRUE, '18.09.2021 00:00:00'],
			[TRUE, '19.09.2021 23:59:59'],
			[FALSE, '20.09.2021 00:00:00'],
		];
	}
}

$test = new ValidatorTest();
$test->run();

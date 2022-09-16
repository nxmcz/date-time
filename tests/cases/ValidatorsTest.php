<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\Utils\Validators;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ValidatorsTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerTimestampFormats
	 */
	public function testIsTimestamp(bool $state, mixed $input): void
	{
		Assert::same($state, Validators::isTimestamp($input));
	}

	public function providerTimestampFormats(): array
	{
		return [
			[TRUE, 1654077600],
			[TRUE, '1654077600'],
			[TRUE, 1654077600.0],
			[TRUE, '32400'],
			[TRUE, 32400.0],
			[TRUE, '32400.0'],
			[TRUE, 32400],
			[TRUE, 32401],

			[FALSE, 31999],
			[FALSE, '31999'],
			[FALSE, 1654077600.89631],

			[FALSE, 'now'],
			[FALSE, 'foo baz'],
			[FALSE, ''],
			[FALSE, NULL],
		];
	}

	/**
	 * @dataProvider providerDatesFormats
	 */
	public function testIsDate(bool $state, mixed $input): void
	{
		Assert::same($state, Validators::isDate($input));
	}

	public function providerDatesFormats(): array
	{
		return [
			[TRUE, '2022-07-20 12:13:45'],
			[TRUE, '2022-07-20'],
			[TRUE, '12:13:45'],
			[TRUE, '12:13:45.239'],
			[TRUE, '12:13'],
			[TRUE, '20.7'],
			[FALSE, 'foo'],
			[FALSE, ''],
			[FALSE, NULL],
			[FALSE, '2022-07-40 12:13:45'],
			[FALSE, '2022-13-20 12:13:45'],
			[FALSE, '-1-07-20 12:13:45'],
			[FALSE, '2022-07-20 25:13:45'],
			[FALSE, '2022-07-20 25:00:01'],
			[FALSE, '2022-07-20 12:60:45'],
			[FALSE, '2022-07-20 12:13:61'],
			[FALSE, '07-20 12:13:45'],
		];
	}
}

$test = new ValidatorsTest();
$test->run();
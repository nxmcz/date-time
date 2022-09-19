<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
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
	 * @dataProvider providerDatesFormats
	 */
	public function testIsDate(bool $isOk, mixed $input): void
	{
		if ($isOk) {
			Assert::type(DT::class, DT::create($input));
		} else {
			Assert::exception(fn() => DT::create($input), BadFormatException::class);
		}

	}

	public function providerDatesFormats(): array
	{
		return [
			[TRUE, '2022-07-20 12:13:45'],
			[TRUE, '2022-07-20'],
			[TRUE, '12:13:45'],
			[TRUE, '12:13:45.239'],
			[TRUE, '12:13'],
			[TRUE, '2022-02-28T12:13:52Z'],
			[FALSE, 'foo'],
			//[FALSE, ''], // ??
			[FALSE, '2022-07-40 12:13:45'],
			[FALSE, '2022-13-20 12:13:45'],
			[FALSE, '-1-07-20 12:13:45'],
			[FALSE, '2022-07-20 25:13:45'],
			[FALSE, '2022-07-20 25:00:01'],
			[FALSE, '2022-07-20 12:60:45'],
			[FALSE, '2022-07-20 12:13:61'],
			[FALSE, '07-20 12:13:45'],
			[FALSE, '2022-13-30T12:13:52Z'], # another format
			[FALSE, '2022-02-32T12:13:52Z'], # another format
			[FALSE, '2022-13-28T25:13:52Z'],

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

			[TRUE, 'now'],
		];
	}
}

$test = new ValidatorsTest();
$test->run();
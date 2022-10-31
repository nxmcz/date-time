<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateTime;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\Utils\Formatter;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class DateConversionTest extends AbstractTestCase
{
	public function testGetMillis()
	{
		$dt = DT::create('2022-07-20 19:00:00.677');
		Assert::same(677000, $dt->getMillisPart());

		$dt = DT::create('2022-07-20 19:00:00.0');
		Assert::same(0, $dt->getMillisPart());

		$dt = DT::create('2022-07-20 19:00:00');
		Assert::same(0, $dt->getMillisPart());

		$dt = DT::create('2022-07-20 19:00:00.123456789');
		Assert::same(123456, $dt->getMillisPart());

		$dt = DT::create('2022-07-20 19:00:00.1234569');
		Assert::same(123456, $dt->getMillisPart());
	}


	/**
	 * @dataProvider dataForGetMinute
	 */
	public function testGetMinute(int $minute, string $time): void
	{
		Assert::same($minute, DT::create($time)->getMinute());
	}

	public function dataForGetMinute(): array
	{
		return [
			[59, '11:59:00'],
			[59, '11:59:59'],
			[1, '11:01:00'],
			[1, '11:01:59'],
			[0, '11:00:00'],
			[0, '11:00:18'],
		];
	}
}

$test = new DateConversionTest();
$test->run();

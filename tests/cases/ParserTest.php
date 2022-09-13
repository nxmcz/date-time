<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Parser;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class ParserTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerWeekFormatData
	 */
	public function testWeek(bool $state, mixed $input): void
	{
		$res = Parser::toWeek($input);
		if($state) {
			Assert::type(DT::class, $res);
		} else {
			Assert::null($res);
		}
	}

	public function providerWeekFormatData(): array
	{
		return [
			[FALSE, 'invalid*input'],
			[FALSE, ''],
			[FALSE, '1-5'],
			[FALSE, '2022-53'],
			[FALSE, '2022-52'],
			[FALSE, '2022-1'],
			[FALSE, '2022-01'],
			[FALSE, '2022-15-5'],
			[FALSE, '202215'],
			[FALSE, '2020-52'],
			[FALSE, '2020-X01'],
			[FALSE, '2020-W1'],
			[FALSE, '2030-W101'],
			[FALSE, '2020-W10-10'],
			[FALSE, '2020W10'],
			[FALSE, '2020-W10W'],
			[FALSE, '2020-W10-'],
			[FALSE, '20-W25'],
			[FALSE, '2-W25'],
			[FALSE, '2-W2'],
			[FALSE, '2-2'],
			[FALSE, '0001-2'],
			[FALSE, '2022-W53'],
			[FALSE, NULL], // current one

			[TRUE, '0001-W01'],
			[TRUE, '2022-W05'],
			[TRUE, '2022-W52'],
			[TRUE, '2022-W01'],
			[TRUE, DT::create()],
		];
	}

	/**
	 * @dataProvider providerMonthFormatData
	 */
	public function testMonth(bool $state, mixed $input): void
	{
		$res = Parser::toMonth($input);
		if($state) {
			Assert::type(DT::class, $res);
		} else {
			Assert::null($res);
		}
	}

	public function providerMonthFormatData(): array
	{
		return [
			[FALSE, 'invalid*input'],
			[FALSE, ''],
			[FALSE, '1-5'],
			[FALSE, '2022-13'],
			[FALSE, '2022-0'],
			[FALSE, '2022--1'],
			[FALSE, '2022-0'],
			[FALSE, '2022-1'],
			[FALSE, '2022-9'],
			[FALSE, '2022-00'],
			[FALSE, '1-1'],
			[FALSE, NULL], // current one

			[TRUE, '0001-01'],
			[TRUE, '2022-01'],
			[TRUE, '2022-05'],
			[TRUE, '2022-10'],
			[TRUE, '2022-12'],
			[TRUE, DT::create()],
		];
	}

	/**
	 * @dataProvider providerDayFormatData
	 */
	public function testParseDay(bool $state, mixed $input): void
	{
		$res = Parser::toDay($input);
		if($state) {
			Assert::type(DT::class, $res);
		} else {
			Assert::null($res);
		}
	}

	public function providerDayFormatData(): array
	{
		return [
			[FALSE, 'invalid*input'],
			[FALSE, ''],
			[FALSE, '1-5-1'],
			[FALSE, '2022-12-5'],
			[FALSE, '2022-0-5'],
			[FALSE, '2022--1-05'],
			[FALSE, '2022-00-05'],
			[FALSE, '2022-01-1'],
			[FALSE, '2022-00-00'],
			[FALSE, '2022-02-30'],
			[FALSE, '1-1'],
			[FALSE, NULL], // current one

			[TRUE, '0001-01-01'],
			[TRUE, '2022-01-20'],
			[TRUE, '2022-05-02'],
			[TRUE, '2022-10-10'],
			[TRUE, '2022-12-31'],
			[TRUE, DT::create()],
		];
	}
}

$test = new ParserTest();
$test->run();
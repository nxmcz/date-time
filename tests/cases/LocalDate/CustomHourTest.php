<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\LocalDate;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class CustomHourTest extends AbstractTestCase
{
	/**
	 * @dataProvider provideValidCases
	 */
	public function testValidCases(string $htmlFormat, string $convertedHtmlFormat, int $hour): void
	{
		$case = LocalDate\CustomHour::createFromHtml($htmlFormat);
		Assert::same($hour, $case->getNumber());
		Assert::same((string) $case, $convertedHtmlFormat);
	}

	public function provideValidCases(): \Generator
	{
		yield['2022-11-05 22:00:00', '2022-11-05 22:00:00', 22];
		yield['2022-11-05 00:00:00', '2022-11-05 00:00:00', 0];
		yield['2022-11-05 22:00:01', '2022-11-05 22:00:01', 22];
		yield['2022-11-05 22:01:00', '2022-11-05 22:01:00', 22];
		yield['2022-11-05 22:01:01', '2022-11-05 22:01:01', 22];
		yield['2022-11-05 24:00:01', '2022-11-06 00:00:01', 0];
	}

	public function testException(): void
	{
		Assert::exception(fn () => LocalDate\CustomHour::createFromHtml('2022-11-05 99:00:00'), BadFormatException::class);
		Assert::exception(fn () => LocalDate\CustomHour::createFromHtml('2022-11-05 00:99:00'), BadFormatException::class);
		Assert::exception(fn () => LocalDate\CustomHour::createFromHtml('2022-11-05 00:00:99'), BadFormatException::class);

		Assert::exception(fn () => LocalDate\CustomHour::createFromHtml('2022-11-99 00:00:00'), BadFormatException::class);
		Assert::exception(fn () => LocalDate\CustomHour::createFromHtml('2022-99-05 00:00:00'), BadFormatException::class);
		Assert::exception(fn () => LocalDate\CustomHour::createFromHtml('ui-11-05 00:00:00'), BadFormatException::class);
	}

	/**
	 * @dataProvider provideDuration
	 */
	public function testDuration(int $year, int $month, int $day, string $from, string $to): void
	{
		$case = new LocalDate\Day($year, $month, $day);

		Assert::same($case->difference()->getStart()->toHumanString(), $from);
		Assert::same($case->difference()->getEnd()->toHumanString(), $to);
	}

	public function provideDuration(): \Generator
	{
		yield [2022, 11, 5, '2022-11-05 00:00:00', '2022-11-06 00:00:00'];
		yield [2022, 12, 31, '2022-12-31 00:00:00', '2023-01-01 00:00:00'];
	}

	public function testIsCurrent(): void
	{
		$now = DT::now();
		$previous = $now->modifyDays(-1);

		$caseNow = new LocalDate\Day($now->getYear(), $now->getMonth(), $now->getDay());
		$casePrevious = new LocalDate\Day($previous->getYear(), $previous->getMonth(), $previous->getDay());

		Assert::true($caseNow->isCurrent());
		Assert::false($casePrevious->isCurrent());
	}
}

$test = new CustomHourTest();
$test->run();

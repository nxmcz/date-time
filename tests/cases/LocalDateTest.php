<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\LocalDate;
use Noxem\DateTime\LocalTime;
use Noxem\DateTime\Utils\Parser;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class LocalDateTest extends AbstractTestCase
{
	public function testCreate(): void
	{
		Assert::true( LocalDate::create(DT::create('2022-05-11 00:00:00'))->getDT()->areEquals(DT::create('2022-05-11 00:00:00')) );
		Assert::true( LocalDate::create(DT::create('2022-05-11 12:34:56'))->getDT()->areEquals(DT::create('2022-05-11 00:00:00')) );
		Assert::true( LocalDate::create("2022-05-11")->getDT()->areEquals(DT::create('2022-05-11 00:00:00')) );
		Assert::false( LocalDate::create("2022-05-11")->getDT()->areEquals(DT::create('2022-05-11 00:00:01')) );
		Assert::true( LocalDate::createFromParts(2022,5 ,11)->getDT()->areEquals(DT::create('2022-05-11 00:00:00')) );
	}

	public function testCreateFromParts(): void
	{
		Assert::exception(fn() => LocalDate::createFromParts(2022,0 ,11), BadFormatException::class);
		Assert::exception(fn() => LocalDate::createFromParts(-9999999,5 ,11), BadFormatException::class);
		Assert::exception(fn() => LocalDate::createFromParts(2022,5 ,32), BadFormatException::class);
		Assert::exception(fn() => LocalDate::createFromParts(2022,2 ,29), BadFormatException::class);
		Assert::noError(fn() => LocalDate::createFromParts(2020,2 ,29));
	}

	public function testExceptions(): void
	{
		Assert::exception(fn() => LocalDate::create('abcd'), BadFormatException::class);
		Assert::exception(fn() => LocalDate::createFromString('2022-07-20-1'), BadFormatException::class);
	}

	public function testTimezone(): void
	{
		Assert::same(LocalDate::create()->getTimezone()->getName(), date_default_timezone_get());
	}

	/**
	 * @dataProvider dates
	 */
	public function testDays(string $name, int $dayOfWeek, string $date): void
	{
		Assert::same($name, LocalDate::createFromString($date)->getDay()->getName());
		Assert::same($dayOfWeek, LocalDate::createFromString($date)->getDayOfWeek());
		Assert::noError(fn() => LocalDate::createFromString($date));
	}

	public function dates(): array
	{
		return [
			['wednesday', 3, '2022-11-2'],
			['wednesday', 3, '2022-11-02'],
			['monday', 1, '2022-07-04'],
			['monday', 1, '2022-7-04'],
			['monday', 1, '2022-7-4'],
			['sunday', 7, '2022-08-21'],
			['sunday', 7, '2022-8-21'],
		];
	}

	public function testImmutable(): void
	{
		$d1 = LocalDate::createFromString('2022-07-20');
		$d2 = LocalDate::createFromString('2022-07-21');
		$d3 = LocalDate::createFromString('2022-07-22');

		Assert::notSame((string)$d1, (string)$d3);
		Assert::notSame((string)$d1, (string)$d2);
	}

	public function testAreEquals(): void
	{
		$d1 = LocalDate::createFromString('2022-07-20');
		$d2 = LocalDate::createFromString('2022-07-21');
		$d3 = LocalDate::createFromString('2022-07-20');

		Assert::true($d1->areEquals($d3));
		Assert::false($d1->areEquals($d2));
	}

	public function testCreateFromFirstSlashLast(): void
	{
		Assert::true( LocalDate::createFromFirst(2022, 7)->areEquals(LocalDate::createFromString('2022-07-01')) );
		Assert::true( LocalDate::createFromLast(2022, 7)->areEquals(LocalDate::createFromString('2022-07-31')) );
		Assert::false( LocalDate::createFromLast(2022, 7)->areEquals(LocalDate::createFromString('2022-07-30')) );
	}
}

$test = new LocalDateTest();
$test->run();

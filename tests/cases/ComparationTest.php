<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Validators;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class ComparationTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerEqualsData
	 */
	public function testAreEquals(bool $state, DT $a, DT $b): void
	{
		Assert::same(
			$state,
			$a->areEquals($b)
		);

		Assert::same(
			$state,
			$b->areEquals($a)
		);
	}

	public function providerEqualsData(): array
	{
		return [
			[
				TRUE,
				DT::create("2020-07-20 15:00:00"),
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Prague"))
			],
			[
				TRUE,
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Prague"))
			],
			[
				TRUE,
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->assignTimezone("Europe/London")
			],
			[
				TRUE,
				DT::create("2020-09-17 12:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 06:00:00")->assignTimezone("America/New_York")
			],
			[
				TRUE,
				DT::create("2020-09-17 10:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 11:00:00")->assignTimezone("Europe/Moscow")
			],
			[
				TRUE,
				DT::create("2020-09-17 13:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 20:00:00")->assignTimezone("Asia/Tokyo")
			],
			[
				TRUE,
				DT::create("2020-09-17 07:00:00")->assignTimezone("America/New_York"),
				DT::create("2020-09-17 20:00:00")->assignTimezone("Asia/Tokyo")
			],
			[
				FALSE,
				DT::create("2020-07-20 14:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->assignTimezone("Europe/London")
			],
			[
				FALSE,
				DT::create("2020-07-20 14:00:00"),
				DT::create("2020-07-20 14:00:00")->assignTimezone("Europe/London")
			],
		];
	}

	/**
	 * @dataProvider providerIsGreaterThanData
	 */
	public function testIsGreaterThan(bool $state, DT $a, DT $b): void
	{
		Assert::same(
			$state,
			$a->isGreaterThan($b)
		);

		/*if($b->areNotEquals($a)) {
			Assert::same(
				!$state,
				$b->isLessThan($a)
			);
		}*/

	}

	public function providerIsGreaterThanData(): array
	{
		return [
			[
				FALSE,
				DT::create("2020-07-20 15:00:00"),
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Prague"))
			],
			[
				FALSE,
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/London"))
			],
			[
				TRUE,
				DT::create("2020-07-20 15:00:01")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->setTimezone(new \DateTimeZone("Europe/London"))
			],
			[ # SAME so NOT EQUALS
				FALSE,
				DT::create("2020-09-17 13:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 07:00:00")->assignTimezone("America/New_York")
			],
			[
				TRUE,
				DT::create("2020-09-17 13:00:01")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 07:00:00")->assignTimezone("America/New_York")
			],
			[
				FALSE,
				DT::create("2020-09-17 11:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 12:00:00")->setTimezone(new \DateTimeZone("Europe/Moscow"))
			],
			[
				TRUE,
				DT::create("2020-09-17 12:00:01")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 11:00:00")->setTimezone(new \DateTimeZone("Europe/Moscow"))
			],
			[
				FALSE,
				DT::create("2020-09-17 11:00:00")->setTimezone(new \DateTimeZone("Europe/Moscow")),
				DT::create("2020-09-17 12:00:01")->setTimezone(new \DateTimeZone("Europe/Prague"))
			]

		];
	}


	public function testGeneral(): void
	{
		$dt = DT::create("2020-07-20 15:00:00");

		Assert::true($dt->areEquals(DT::create("2020-07-20 15:00:00")));
		Assert::false($dt->areEquals(DT::create("2020-07-20 15:00:01")));
		Assert::false($dt->areEquals(DT::create("2020-07-20 14:59:59")));

		Assert::false($dt->areNotEquals(DT::create("2020-07-20 15:00:00")));
		Assert::true($dt->areNotEquals(DT::create("2020-07-20 15:00:01")));
		Assert::true($dt->areNotEquals(DT::create("2020-07-20 14:59:59")));

		Assert::true($dt->isGreaterThan(DT::create("2020-07-20 14:59:59")));
		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 15:00:00")));

		Assert::false($dt->isLessThan(DT::create("2020-07-20 15:00:00")));
		Assert::true($dt->isLessThan(DT::create("2020-07-20 15:00:01")));
		Assert::false($dt->isLessThanOrEqualTo(DT::create("2020-07-20 14:59:59")));
		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 15:00:00")));
		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 15:00:01")));

		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 14:59:59")));
		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 15:00:00")));
		Assert::false($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 15:00:01")));
	}

	public function testSameTimezone(): void
	{
		$dt = DT::create("2020-07-20 00:00:00");

		Assert::true($dt->areEquals(DT::create("2020-07-20 00:00:00")));
		Assert::false($dt->areEquals(DT::create("2020-07-20 00:00:01")));

		Assert::false($dt->areNotEquals(DT::create("2020-07-20 00:00:00")));
		Assert::true($dt->areNotEquals(DT::create("2020-07-20 00:00:01")));

		Assert::true($dt->isGreaterThan(DT::create("2020-07-19 23:59:59")));
		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 00:00:00")));
		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 00:00:01")));

		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-19 23:59:59")));
		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 00:00:00")));
		Assert::false($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 00:00:01")));

		Assert::false($dt->isLessThan(DT::create("2020-07-19 23:59:59")));
		Assert::false($dt->isLessThan(DT::create("2020-07-20 00:00:00")));
		Assert::true($dt->isLessThan(DT::create("2020-07-20 00:00:01")));

		Assert::false($dt->isLessThanOrEqualTo(DT::create("2020-07-19 23:59:59")));
		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 00:00:00")));
		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 00:00:01")));
	}

	public function testTimezone1(): void
	{
		$dt = DT::create("2020-07-20 00:00:00");

		Assert::false($dt->areEquals(DT::create("2020-07-20 07:00:01")->assignTimezone("Asia/Tokyo")));
		Assert::true($dt->areEquals(DT::create("2020-07-20 07:00:00")->assignTimezone("Asia/Tokyo")));
		Assert::false($dt->areEquals(DT::create("2020-07-20 06:59:59")->assignTimezone("Asia/Tokyo")));

		Assert::true($dt->areNotEquals(DT::create("2020-07-20 07:00:01")->assignTimezone("Asia/Tokyo")));
		Assert::false($dt->areNotEquals(DT::create("2020-07-20 07:00:00")->assignTimezone("Asia/Tokyo")));
		Assert::true($dt->areNotEquals(DT::create("2020-07-20 06:59:59")->assignTimezone("Asia/Tokyo")));

		Assert::true($dt->isLessThan(DT::create("2020-07-20 07:00:01")->assignTimezone("Asia/Tokyo")));
		Assert::false($dt->isLessThan(DT::create("2020-07-20 07:00:00")->assignTimezone("Asia/Tokyo")));
		Assert::false($dt->isLessThan(DT::create("2020-07-20 06:59:59")->assignTimezone("Asia/Tokyo")));

		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 07:00:01")->assignTimezone("Asia/Tokyo")));
		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 07:00:00")->assignTimezone("Asia/Tokyo")));
		Assert::false($dt->isLessThanOrEqualTo(DT::create("2020-07-20 06:59:59")->assignTimezone("Asia/Tokyo")));

		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 07:00:01")->assignTimezone("Asia/Tokyo")));
		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 07:00:00")->assignTimezone("Asia/Tokyo")));
		Assert::true($dt->isGreaterThan(DT::create("2020-07-20 06:59:59")->assignTimezone("Asia/Tokyo")));

		Assert::false($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 07:00:01")->assignTimezone("Asia/Tokyo")));
		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 07:00:00")->assignTimezone("Asia/Tokyo")));
		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 06:59:59")->assignTimezone("Asia/Tokyo")));
	}

	public function testTimezone0(): void
	{
		$dt = DT::create("2020-07-20 00:00:00");

		Assert::false($dt->areEquals(DT::create("2020-07-20 00:00:01")));
		Assert::true($dt->areEquals(DT::create("2020-07-20 00:00:00")));
		Assert::false($dt->areEquals(DT::create("2020-07-19 23:59:59")));

		Assert::true($dt->areNotEquals(DT::create("2020-07-20 00:00:01")));
		Assert::false($dt->areNotEquals(DT::create("2020-07-20 00:00:00")));
		Assert::true($dt->areNotEquals(DT::create("2020-07-19 23:59:59")));

		Assert::true($dt->isLessThan(DT::create("2020-07-20 00:00:01")));
		Assert::false($dt->isLessThan(DT::create("2020-07-20 00:00:00")));
		Assert::false($dt->isLessThan(DT::create("2020-07-19 23:59:59")));

		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 00:00:01")));
		Assert::true($dt->isLessThanOrEqualTo(DT::create("2020-07-20 00:00:00")));
		Assert::false($dt->isLessThanOrEqualTo(DT::create("2020-07-19 23:59:59")));

		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 00:00:01")));
		Assert::false($dt->isGreaterThan(DT::create("2020-07-20 00:00:00")));
		Assert::true($dt->isGreaterThan(DT::create("2020-07-19 23:59:59")));

		Assert::false($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 00:00:01")));
		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-20 00:00:00")));
		Assert::true($dt->isGreaterThanOrEqualTo(DT::create("2020-07-19 23:59:59")));
	}

	public function testTimezoneOffsetBeforeWinterTime(): void
	{
		$dt = DT::create('29.10.2022');

		Assert::equal(0, $dt->getTimezoneOffset($dt));
		Assert::equal(1, $dt->getTimezoneOffset($dt->assignTimezone("Europe/Moscow")));
		Assert::equal(7, $dt->getTimezoneOffset($dt->assignTimezone("Asia/Tokyo")));
		Assert::equal(-6, $dt->getTimezoneOffset($dt->assignTimezone("America/New_York")));
		Assert::equal(-9, $dt->getTimezoneOffset($dt->assignTimezone("America/Los_Angeles")));

		Assert::equal(9, $dt->assignTimezone("America/Los_Angeles")->getTimezoneOffset($dt));
		Assert::equal(16,
			$dt->assignTimezone("America/Los_Angeles")->getTimezoneOffset($dt->assignTimezone("Asia/Tokyo")));
		Assert::equal(-16,
			$dt->assignTimezone("Asia/Tokyo")->getTimezoneOffset($dt->assignTimezone("America/Los_Angeles")));
	}

	public function testTimezoneOffsetAfterWinterTime(): void
	{
		$dt = DT::create('11.11.2022');

		Assert::equal(0, $dt->getTimezoneOffset($dt));
		Assert::equal(2, $dt->getTimezoneOffset($dt->assignTimezone("Europe/Moscow")));
		Assert::equal(8, $dt->getTimezoneOffset($dt->assignTimezone("Asia/Tokyo")));
		Assert::equal(-6, $dt->getTimezoneOffset($dt->assignTimezone("America/New_York")));
		Assert::equal(-9, $dt->getTimezoneOffset($dt->assignTimezone("America/Los_Angeles")));

		Assert::equal(9, $dt->assignTimezone("America/Los_Angeles")->getTimezoneOffset($dt));
		Assert::equal(17,
			$dt->assignTimezone("America/Los_Angeles")->getTimezoneOffset($dt->assignTimezone("Asia/Tokyo")));
		Assert::equal(-17,
			$dt->assignTimezone("Asia/Tokyo")->getTimezoneOffset($dt->assignTimezone("America/Los_Angeles")));
	}
}

$test = new ComparationTest();
$test->run();

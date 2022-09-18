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
	}

	public function providerEqualsData(): array
	{
		return [
			[
				TRUE,
				DT::create("2020-07-20 15:00:00"),
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague"))
			], [
				TRUE,
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague"))
			], [
				TRUE,
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/London"))
			], [ # this one not working
				TRUE,
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/London")),
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
			], [
				TRUE,
				DT::create("2020-09-17 12:00:00")->setTimezone(new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 06:00:00")->setTimezone(new \DateTimeZone("America/New_York"))
			], [ # this one not working
				TRUE,
				DT::create("2020-09-17 10:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 11:00:00")->setTimezone( new \DateTimeZone("Europe/Moscow"))
			], [
				TRUE,
				DT::create("2020-09-17 11:00:00")->setTimezone( new \DateTimeZone("Europe/Moscow")),
				DT::create("2020-09-17 10:00:00")->setTimezone( new \DateTimeZone("Europe/Prague"))
			], [
				TRUE,
				DT::create("2020-09-17 13:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 20:00:00")->setTimezone( new \DateTimeZone("Asia/Tokyo"))
			], [
				TRUE,
				DT::create("2020-09-17 07:00:00")->setTimezone( new \DateTimeZone("America/New_York")),
				DT::create("2020-09-17 20:00:00")->setTimezone( new \DateTimeZone("Asia/Tokyo"))
			], [
				TRUE,
				DT::create("2020-09-17 20:00:00")->setTimezone( new \DateTimeZone("Asia/Tokyo")),
				DT::create("2020-09-17 07:00:00")->setTimezone( new \DateTimeZone("America/New_York"))
			], [
				FALSE,
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/London"))
			], [
				FALSE,
				DT::create("2020-07-20 14:00:00"),
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/London"))
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
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague"))
			], [
				FALSE,
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/London"))
			], [
				FALSE,
				DT::create("2020-07-20 15:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/London"))
			],[
				TRUE,
				DT::create("2020-07-20 15:00:01")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-07-20 14:00:00")->setTimezone( new \DateTimeZone("Europe/London"))
			], [ # SAME so NOT EQUALS
				FALSE,
				DT::create("2020-09-17 13:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 07:00:00")->setTimezone( new \DateTimeZone("America/New_York"))
			], [
				TRUE,
				DT::create("2020-09-17 13:00:01")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 07:00:00")->setTimezone( new \DateTimeZone("America/New_York"))
			], [
				FALSE,
				DT::create("2020-09-17 11:00:00")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 12:00:00")->setTimezone( new \DateTimeZone("Europe/Moscow"))
			], [
				TRUE,
				DT::create("2020-09-17 12:00:01")->setTimezone( new \DateTimeZone("Europe/Prague")),
				DT::create("2020-09-17 11:00:00")->setTimezone( new \DateTimeZone("Europe/Moscow"))
			], [
				FALSE,
				DT::create("2020-09-17 11:00:00")->setTimezone( new \DateTimeZone("Europe/Moscow")),
				DT::create("2020-09-17 12:00:01")->setTimezone( new \DateTimeZone("Europe/Prague"))
			]

		];
	}


	public function testGeneral(): void
	{
		$dt = DT::create("2020-07-20 15:00:00");

		Assert::true( $dt->areEquals( DT::create("2020-07-20 15:00:00") ) );
		Assert::false( $dt->areEquals( DT::create("2020-07-20 15:00:01") ) );
		Assert::false( $dt->areEquals( DT::create("2020-07-20 14:59:59") ) );

		Assert::false( $dt->areNotEquals( DT::create("2020-07-20 15:00:00") ) );
		Assert::true( $dt->areNotEquals( DT::create("2020-07-20 15:00:01") ) );
		Assert::true( $dt->areNotEquals( DT::create("2020-07-20 14:59:59") ) );

		Assert::true( $dt->isGreaterThan( DT::create("2020-07-20 14:59:59") ) );
		Assert::false( $dt->isGreaterThan( DT::create("2020-07-20 15:00:00") ) );
		Assert::true( $dt->isGreaterThan( DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Moscow")) ) );
		Assert::false( $dt->isGreaterThan( DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/London")) ) );

		Assert::false( $dt->isLessThan( DT::create("2020-07-20 15:00:00") ) );
		Assert::true( $dt->isLessThan( DT::create("2020-07-20 15:00:01") ) );
		Assert::false( $dt->isLessThan( DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/Moscow")) ) );
		Assert::true( $dt->isLessThan( DT::create("2020-07-20 15:00:00")->setTimezone(new \DateTimeZone("Europe/London")) ) );
		Assert::false( $dt->isLessThanOrEqualTo( DT::create("2020-07-20 14:59:59") ) );
		Assert::true( $dt->isLessThanOrEqualTo( DT::create("2020-07-20 15:00:00") ) );
		Assert::true( $dt->isLessThanOrEqualTo( DT::create("2020-07-20 15:00:01") ) );

		Assert::true( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 14:59:59") ) );
		Assert::true( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 15:00:00") ) );
		Assert::false( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 15:00:01") ) );
		Assert::true( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 16:00:00")->setTimezone(new \DateTimeZone("Europe/Moscow")) ) );
		Assert::false( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 16:00:01")->setTimezone(new \DateTimeZone("Europe/Moscow")) ) );
		Assert::true( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 14:00:00")->setTimezone(new \DateTimeZone("Europe/London")) ) );
		Assert::false( $dt->isGreaterThanOrEqualTo( DT::create("2020-07-20 14:00:01")->setTimezone(new \DateTimeZone("Europe/London")) ) );
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
}

$test = new ComparationTest();
$test->run();
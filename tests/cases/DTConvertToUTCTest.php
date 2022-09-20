<?php declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Noxem\DateTime\Utils\Formatter;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class DTConvertToUTCTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerOfDifferentTimezonesToUTC
	 */
	public function testConversion(int $offset, string $timezone, string $eq): void # Europe/Prague is different 2 hours
	{
		$dt = DT::create("2022-09-29T00:01:10Z")->setTimezone(new \DateTimeZone($timezone));
		$r = DT::create($eq)->resetTimezone($timezone);
		$utc = DT::create("2022-09-29T00:01:10Z");

		Assert::same($offset, $utc->getTimezoneOffset($dt)); // offset of UTC to default
		Assert::same($offset*(-1), $dt->getTimezoneOffset($utc)); // offset of UTC to default
		Assert::true($dt->areEquals($utc));
		Assert::true($utc->areEquals($dt));

		Assert::true($r->areEquals($dt));
		Assert::true($r->areEquals($utc));


		Assert::same("2022-09-29T00:01:10Z", $r->toIso8601ZuluString());
	}

	public function providerOfDifferentTimezonesToUTC(): array {
		return [
			[-6, "America/New_York", "2022-09-28 20:01:10"],
			[0, "Europe/Prague", "2022-09-29 02:01:10"],
			[1, "Europe/Moscow", "2022-09-29 03:01:10"],
			[7, "Asia/Tokyo", "2022-09-29 09:01:10"],
			[-9, "America/Los_Angeles", "2022-09-28 17:01:10"],
		];
	}

	public function testUTC(): void {
		Assert::true(
			DT::create("2022-09-29T00:01:10Z")
				->areEquals(DT::create("2022-09-29 02:01:10"))
		);

		Assert::true(
			DT::create("2022-09-29T00:01:10Z")
				->areEquals(DT::create("2022-09-29 02:01:10")->resetTimezone("Europe/Prague"))
		);
	}
}

$test = new DTConvertToUTCTest();
$test->run();
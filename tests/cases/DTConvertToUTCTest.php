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
		$dt = DT::create($eq)->setTimezone(new \DateTimeZone($timezone));
		$utc = DT::create("2022-09-29T00:01:10Z");

		Assert::same($offset-2, ($dt->getOffset() - $utc->getOffset())/3600);
		//Assert::same($utc->getTimezone()->getName(), $dt->getTimezone()->getName());
		//Assert::true($utc->setTimezone(new \DateTimeZone($timezone))->areEquals($dt));
		Assert::true($dt->areEquals($utc));
		Assert::true($utc->areEquals($dt));

		Assert::same("2022-09-29T00:01:10Z", $dt->toIso8601ZuluString());
	}

	public function providerOfDifferentTimezonesToUTC(): array {
		return [
			[-4, "America/New_York", "2022-09-28 20:01:10"],
			[2, "Europe/Prague", "2022-09-29 02:01:10"],
			[3, "Europe/Moscow", "2022-09-29 03:01:10"],
			[9, "Asia/Tokyo", "2022-09-29 09:01:10"],
			[-7, "America/Los_Angeles", "2022-09-28 17:01:10"],
		];
	}
}

$test = new DTConvertToUTCTest();
$test->run();
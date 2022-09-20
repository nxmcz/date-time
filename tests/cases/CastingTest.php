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
class CastingTest extends AbstractTestCase
{
	public function testIsDate(): void
	{
		$prague = DT::create("2022-07-05 15:30:00")->resetTimezone("Europe/Prague");
		$tokyo = DT::create("2022-07-05 15:30:00")->resetTimezone("Asia/Tokyo");

		Assert::same("2022-07-05 08:30:00", $tokyo->toLocalHumanString());
		Assert::same("2022-07-05 15:30:00", $tokyo->toHumanString());

		Assert::same("2022-07-05T06:30:00Z", $tokyo->toIso8601ZuluString());
		Assert::same("2022-07-05T13:30:00Z", $prague->toIso8601ZuluString());

		Assert::same("2022-07-05T15:30:00Z", $tokyo->toUtcString());
		Assert::same("2022-07-05T15:30:00Z", $prague->toUtcString());

		Assert::same("2022-07-05T08:30:00", $tokyo->toDateTimeLocalString());
		Assert::same("2022-07-05T15:30:00", $prague->toDateTimeLocalString());

		Assert::same("2022-07-05T15:30:00+09:00", $tokyo->toW3cString());
		Assert::same("2022-07-05T15:30:00+02:00", $prague->toW3cString());

		Assert::same("Tue, 05 Jul 2022 15:30:00 +0900", $tokyo->toRssString());
		Assert::same("Tue, 05 Jul 2022 15:30:00 +0200", $prague->toRssString());

		Assert::same("2022-07-05T15:30:00+09:00", $tokyo->toAtomString());
		Assert::same("2022-07-05T15:30:00+02:00", $prague->toAtomString());
	}
}

$test = new CastingTest();
$test->run();
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
	public function testCastMethods(): void
	{
		$prague = DT::create("2022-07-05 15:30:00")->assignTimezone("Europe/Prague");
		$tokyo = DT::create("2022-07-05 15:30:00")->assignTimezone("Asia/Tokyo");
		$la = DT::create("2022-07-04 22:00:00")->assignTimezone("America/Los_Angeles");

		Assert::same("2022-07-05 08:30:00", $tokyo->toLocalHumanString());
		Assert::same("2022-07-05 15:30:00", $prague->toLocalHumanString());
		Assert::same("2022-07-05 07:00:00", $la->toLocalHumanString());

		Assert::same("2022-07-05 15:30:00", $prague->toHumanString());
		Assert::same("2022-07-05 15:30:00", $tokyo->toHumanString());
		Assert::same("2022-07-04 22:00:00", $la->toHumanString());

		Assert::same("2022-07-05T06:30:00Z", $tokyo->toIso8601ZuluString());
		Assert::same("2022-07-05T13:30:00Z", $prague->toIso8601ZuluString());
		Assert::same("2022-07-05T05:00:00Z", $la->toIso8601ZuluString());

		Assert::same("2022-07-05T15:30:00", $tokyo->toDateTimeString());
		Assert::same("2022-07-05T15:30:00", $prague->toDateTimeString());
		Assert::same("2022-07-04T22:00:00", $la->toDateTimeString());

		Assert::same("2022-07-05T08:30:00", $tokyo->toLocalDateTimeString());
		Assert::same("2022-07-05T15:30:00", $prague->toLocalDateTimeString());
		Assert::same("2022-07-05T07:00:00", $la->toLocalDateTimeString());

		Assert::same("2022-07-05", $tokyo->toHtmlDate());
		Assert::same("2022-07-05", $prague->toHtmlDate());
		Assert::same("2022-07-04", $la->toHtmlDate());

		Assert::same("2022-07-05T15:30:00+09:00", $tokyo->toW3cString());
		Assert::same("2022-07-05T15:30:00+02:00", $prague->toW3cString());

		Assert::same("Tue, 05 Jul 2022 15:30:00 +0900", $tokyo->toRssString());
		Assert::same("Tue, 05 Jul 2022 15:30:00 +0200", $prague->toRssString());

		Assert::same("2022-07-05T15:30:00+09:00", $tokyo->toAtomString());
		Assert::same("2022-07-05T15:30:00+02:00", $prague->toAtomString());

		Assert::same("2022-07-05", $tokyo->toHtmlDate());
		Assert::same("2022-07-05", $prague->toHtmlDate());

		Assert::same("2022-07", $tokyo->toHtmlMonth());
		Assert::same("2022-07", $prague->toHtmlMonth());

		Assert::same("2022-W27", $tokyo->toHtmlWeek());
		Assert::same("2022-W27", $prague->toHtmlWeek());

		Assert::same("2022", $tokyo->toHtmlYear());
		Assert::same("2022", $prague->toHtmlYear());
	}
}

$test = new CastingTest();
$test->run();

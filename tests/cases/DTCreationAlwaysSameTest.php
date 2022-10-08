<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateTime;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class DTCreationAlwaysSameTest extends AbstractTestCase
{
	/**
	 * @dataProvider providerOfFormatsToCreatingObject
	 */
	public function testCreateWith($suspect): void
	{

		$dt = DT::create("2022-02-28 12:13:52");
		Assert::true($dt->areEquals(DT::create($suspect)->setTimezone(new \DateTimeZone("Europe/Prague"))));
	}

	public function providerOfFormatsToCreatingObject(): array
	{
		return [
			['2022-02-28 12:13:52'],
			['2022-02-28T11:13:52Z'],
		];
	}

	public function testCreateFromUTC(): void
	{

		$dt = DT::createFromUTC("2022-02-28T11:13:52Z");
		Assert::true($dt->areEquals(DT::create("2022-02-28 12:13:52")->assignTimezone("Europe/Prague")));
	}
}

$test = new DTCreationAlwaysSameTest();
$test->run();

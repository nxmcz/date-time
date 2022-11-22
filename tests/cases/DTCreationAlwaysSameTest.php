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
		$dt = DT::create('2022-02-28 12:13:52');
		Assert::true($dt->areEquals(DT::create($suspect)->setTimezone(new \DateTimeZone('Europe/Prague'))));
	}

	public function providerOfFormatsToCreatingObject(): array
	{
		return [
			['2022-02-28 12:13:52'],
			['2022-02-28T11:13:52Z'],
		];
	}

	public function testFromUTC(): void
	{
		Assert::same(DT::fromUTC('2022-02-28T11:00:00Z')->toHumanString(), '2022-02-28 12:00:00');
		Assert::same(DT::fromUTC('2022-02-28T11:00:00Z')->toHumanString(), '2022-02-28 12:00:00');
		Assert::same(DT::fromUTC('2022-05-05T15:00:00Z')->toHumanString(), '2022-05-05 17:00:00');
		//Assert::same(DT::fromUTC('2022-05-05T15:00:00')->toHumanString(), '2022-05-05 16:00:00');
		Assert::same(DT::fromUTC('2022-05-05 15:00:00')->toHumanString(), '2022-05-05 17:00:00');
	}

	public function testDefaultWithUTC(): void
	{
		Assert::same(DT::create('2022-02-28T11:00:00Z')->toHumanString(), '2022-02-28 12:00:00'); // trying create with UTC zulu
		Assert::same(DT::create('2022-02-28T11:00:00+01:00')->toHumanString(), '2022-02-28 11:00:00');
		Assert::same(DT::create('2022-02-28T11:00:00+02:00')->toHumanString(), '2022-02-28 10:00:00');
	}
}

$test = new DTCreationAlwaysSameTest();
$test->run();

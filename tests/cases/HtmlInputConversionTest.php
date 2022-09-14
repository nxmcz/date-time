<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\Attributes\HtmlInputConversion;
use Noxem\DateTime\DT;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class HtmlInputConversionTest extends AbstractTestCase
{
	public function testInit()
	{
		$dt = DT::create("2022-07-20 12:34:56");
		Assert::type(HtmlInputConversion::class, $dt->convertInput());
		Assert::same("2022-07-20", $dt->convertInput()->toDate());
		Assert::same("2022-07-20T12:34:56", $dt->convertInput()->toDateTime());
		Assert::same("2022-07-20T12:34:56Z", $dt->convertInput()->toUTC());
		Assert::same("2022-07", $dt->convertInput()->toMonth());
		Assert::same("2022-W29", $dt->convertInput()->toWeek());
		Assert::same("2022", $dt->convertInput()->toYear());
	}
}

$test = new HtmlInputConversionTest();
$test->run();
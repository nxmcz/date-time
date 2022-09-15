<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DTime;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class DTimeTest extends AbstractTestCase
{
	public function testConversion(): void
	{
		$dtime = DTime::create(120);

		Assert::same(120000 , $dtime->getMillis());
		Assert::same(120, $dtime->getSeconds());
		Assert::same(2.0 , $dtime->getMinutes());
		Assert::same( 120/3600, $dtime->getHours());
	}
}

$test = new DTimeTest();
$test->run();
<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT as DateTime;
use Noxem\DateTime\Exception\BadFormatException;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class AttributesTest extends AbstractTestCase
{
	public function testConverts()
	{
		Assert::same(1654077600, DateTime::create(1654077600)->getTimestamp());
		Assert::same(1654077600000, DateTime::create(1654077600)->getMillis());
	}
}

$test = new AttributesTest();
$test->run();
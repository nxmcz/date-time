<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\Exception\BadFormatException;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class ExceptionTest extends AbstractTestCase
{
	public function testBadFormatException(): void
	{
		Assert::exception(
			fn() => throw BadFormatException::create(),
			BadFormatException::class,
			"Bad DateTimeImmutable constructors format. Must be INT >= 32400 or string-date in format like: YYYY-MM-DD H:i:s"
		);

		Assert::exception(
			fn() => throw BadFormatException::create()->withMessage("Foo bar BAZ"),
			BadFormatException::class,
			"Foo bar BAZ"
		);
	}
}

$test = new ExceptionTest();
$test->run();
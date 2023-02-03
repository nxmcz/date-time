<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\Exception\BadFormatException;
use Noxem\DateTime\LocalTime;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class LocalTimeTest extends AbstractTestCase
{
	/**
	 * @dataProvider times
	 */
	public function testResults(bool $exception, mixed $suspect, array $results): void
	{
		if ($exception) {
			Assert::exception(fn () => LocalTime::create($suspect), BadFormatException::class);
		} else {
			$t = LocalTime::create($suspect);

			Assert::same(
				$results,
				[
					$t->getHour(),
					$t->getMinute(),
					$t->getSecond(),
					$t->getMillis(),
				]
			);
		}
	}

	public function times(): array
	{
		return [
			[false, '12:34:56', [12, 34, 56, 0]],
			[false, '00:34:56', [0, 34, 56, 0]],
			[false, '12:00:56', [12, 0, 56, 0]],
			[false, '12:34:00', [12, 34, 0, 0]],
			[false, '00:00:00', [0, 0, 0, 0]],
		];
	}

	/**
	 * @dataProvider stringsCreation
	 */
	public function testCreateFromString(bool $exception, mixed $suspect, array $results = []): void
	{
		if ($exception) {
			Assert::exception(fn () => LocalTime::create($suspect), BadFormatException::class);
		} else {
			$t = LocalTime::createFromString($suspect);

			Assert::noError(fn () => LocalTime::createFromParts(
				$t->getHour(),
				$t->getMinute(),
				$t->getSecond(),
				$t->getMillis()
			));

			Assert::same(
				$results,
				[
					$t->getHour(),
					$t->getMinute(),
					$t->getSecond(),
					$t->getMillis(),
				]
			);
		}
	}

	public function stringsCreation(): array
	{
		return [
			[false, '12:34:56', [12, 34, 56, 0]],
			[false, '12:34', [12, 34, 0, 0]],
			[false, '22:00', [22, 0, 0, 0]],
			[false, '23:59:59', [23, 59, 59, 0]],
			[false, '0:0:0', [0, 0, 0, 0]],
			[false, '00:00:00', [0, 0, 0, 0]],

			[true, '00:00:61'],
			[true, '12:60:00'],
			[true, '12:34:'],
			[true, '12:61'],
			[true, '25:34:'],
			[true, '-6:34:'],
		];
	}

	public function testCreate(): void
	{
		Assert::exception(fn () => LocalTime::create('abcd'), BadFormatException::class);
		Assert::exception(fn () => LocalTime::createFromString('22-07-20-1'), BadFormatException::class);
		Assert::exception(fn () => LocalTime::createFromString('22:07:20:1'), BadFormatException::class);

		Assert::type(LocalTime::class, LocalTime::createFromParts(22, 7, 20));
		Assert::type(LocalTime::class, LocalTime::create(LocalTime::createFromString('22:07:20')));
	}

	public function testHideSeconds(): void
	{
		Assert::same('22:07:20', (string) LocalTime::createFromString('22:07:20'));
		Assert::same('22:07', (string) LocalTime::createFromString('22:07:20')->hideSeconds());
	}
}

$test = new LocalTimeTest();
$test->run();

<?php

namespace Tests\Unit;

use Nette\Utils\DateTime;
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


test('create', function() {
	Assert::same(1654077600, DT::create(1654077600)->getTimestamp());
	Assert::same(1654077600, DT::create('1654077600')->getTimestamp());

	Assert::same(1654077600, DT::create('2022-06-01 12:00:00')->getTimestamp());
	Assert::same(time(), DT::create('now')->getTimestamp());
	Assert::same( time(), DT::create()->getTimestamp());

	Assert::type(DT::class, DT::create());
	Assert::exception(fn() => DT::create('foo foo Baz'), BadFormatException::class);

	Assert::true(
		DT::create("20.7.1993 00:30:00")
			->areEquals(DT::create(743121000))
	);

	Assert::true(
		DT::create()
			->areEquals(DT::create('foo foo Baz', FALSE, 'now'))
	);

	Assert::true(
		DT::create('2022-07-20 14:13:12')
			->areEquals(DT::create('foo foo Baz', FALSE, '2022-07-20 14:13:12'))
	);

	Assert::true(
		DT::create()->setTime(20, 7, 0)
			->areEquals(DT::create('20.7'))
	);

	Assert::true(
		DT::create()->setTime(13, 55, 27)
			->areEquals(DT::create('13:55:27'))
	);

	Assert::true(
		DT::create()->setTime(13, 55, 27, 689)
			->areEquals(DT::create('13:55:27.689'))
	);
});

test('getOrCreateInstance', function() {
	Assert::same(1654077600, DT::getOrCreateInstance(1654077600)->getTimestamp());

	$class = DT::create();
	Assert::same($class, DT::getOrCreateInstance($class));

	Assert::same(1654077600, DT::create('2022-06-01 12:00:00')->getTimestamp());
	Assert::same( time(), DT::getOrCreateInstance()->getTimestamp());

	Assert::type(DT::class, DT::getOrCreateInstance());
	Assert::exception(fn() => DT::getOrCreateInstance('foo foo Baz'), BadFormatException::class);

	Assert::true(DT::create()->areEquals(DT::getOrCreateInstance(new \DateTimeImmutable())));
	Assert::true(DT::create()->areEquals(DT::getOrCreateInstance(new DateTime())));
	Assert::false(DT::create()->areEquals(DT::getOrCreateInstance((new DateTime())->setTimezone(new \DateTimeZone('America/New_York')))));
});

test('areEquals', function() {
	Assert::true(
		DT::create('2022-07-20 01:00:00')
			->areEquals( DT::create('2022-07-20 01:00:00') )
	);

	Assert::true(
		DT::create('2022-07-20 01:00:00')
			->setTimezone(new \DateTimeZone('Europe/Prague'))
			->areEquals( DT::create('2022-07-20 01:00:00')->setTimezone(new \DateTimeZone('Europe/Prague')) )
	);

	Assert::false(
		DT::create('2022-07-20 01:00:00')
			->setTimezone(new \DateTimeZone('Europe/Prague'))
			->areEquals( DT::create('2022-07-20 01:00:00')
				->setTimezone(new \DateTimeZone('America/New_York')) )
	);

	Assert::false(
		DT::create('2022-07-20 01:00:00')
			->areEquals( DT::create('2022-07-20 01:00:00')
				->setTimezone(new \DateTimeZone('America/New_York')) )
	);
});

test('isFuture', function() {
	Assert::true( DT::create('now')->modify('+1 year')->isFuture() );
	Assert::true( DT::create('now')->modify('+1 seconds')->isFuture() );
	Assert::false( DT::create('now')->isFuture() );
});

test('difference', function() {
	Assert::same(59,
		DT::create('2022-07-20 15:44:30')
			->difference( DT::create('2022-07-20 15:45:29') )
			->seconds()
	);

	Assert::same(3.0,
		DT::create('2022-07-20 15:44:30')
			->difference( DT::create('2022-07-20 15:47:30') )
			->minutes()
	);

	Assert::true(
		DT::create('2022-07-20 15:44:30')
			->difference( DT::create('2022-07-20 15:47:30') )
			->isValid()
	);

	Assert::exception(fn() =>
		DT::create('2022-07-20 15:44:30')
			->difference( DT::create('2022-07-20 15:44:30'), TRUE )
			->isValid()
		, \InvalidArgumentException::class
	);

	Assert::exception(fn() =>
		DT::create('2022-07-20 15:44:30')
				->difference( DT::create('2022-07-20 15:44:29'), TRUE  )
				->isValid()
		, \InvalidArgumentException::class
	);
});

test('dateParts', function() {
	$dt = DT::create('2022-07-20 19:00:00');

	Assert::same(2022, $dt->year());
	Assert::same(7, $dt->month());
	Assert::same(20, $dt->day());
	Assert::same(19, $dt->hour());
	Assert::same(29, $dt->week());
});

test('parseInputs', function() {
	$dt = DT::create('2022-07-20 19:00:00');

	Assert::same("2022-07-20T19:00:00", $dt->toDateTimeInput());
	Assert::same("2022-07-20", $dt->toDateInput());
	Assert::same("2022-W29", $dt->toWeekInput());
	Assert::same("2022-07", $dt->toMonthInput());
	Assert::same("2022", $dt->toYearInput());
});


test('converts', function() {
	$dt = DT::create('2022-07-20 19:00:00.677');
	Assert::same(677000, $dt->millis());

	$dt = DT::create('2022-07-20 19:00:00.0');
	Assert::same(0, $dt->millis());

	$dt = DT::create('2022-07-20 19:00:00');
	Assert::same(0, $dt->millis());

	$dt = DT::create('2022-07-20 19:00:00.123456789');
	Assert::same(123456, $dt->millis());

	$dt = DT::create('2022-07-20 19:00:00.1234569');
	Assert::same(123456, $dt->millis());
});

test('__toString', function() {
	Assert::same('2022-07-20 19:00:00', (string)DT::create('2022-07-20 19:00:00'));
});
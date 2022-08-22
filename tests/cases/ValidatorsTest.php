<?php

namespace Tests;

use Noxem\DateTime\Utils\Validators;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


test('isTimestamp', function() {
	Assert::true( Validators::isTimestamp(1654077600) );
	Assert::true( Validators::isTimestamp('1654077600') );
	Assert::true( Validators::isTimestamp(1654077600.0) );
	Assert::true( Validators::isTimestamp('32400') );
	Assert::true( Validators::isTimestamp(32400.0) );
	Assert::true( Validators::isTimestamp('32400.0') );
	Assert::true( Validators::isTimestamp(32400) );
	Assert::true( Validators::isTimestamp(32401) );

	Assert::false( Validators::isTimestamp(31999) );
	Assert::false( Validators::isTimestamp('31999') );
	Assert::false( Validators::isTimestamp(1654077600.89631) );

	Assert::false( Validators::isTimestamp('now') );
	Assert::false( Validators::isTimestamp('foo baz') );
	Assert::false( Validators::isTimestamp('') );
	Assert::false( Validators::isTimestamp(NULL) );
});

test('isDate', function() {
	Assert::true( Validators::isDate('2022-07-20 12:13:45') );
	Assert::true( Validators::isDate('2022-07-20') );
	Assert::true( Validators::isDate('12:13:45') );
	Assert::true( Validators::isDate('12:13:45.239') );
	Assert::true( Validators::isDate('12:13') );
	Assert::true( Validators::isDate('20.7') );

	Assert::false( Validators::isDate('foo') );
	Assert::false( Validators::isDate('') );
	Assert::false( Validators::isDate(NULL) );
	Assert::false( Validators::isDate('2022-07-40 12:13:45') );
	Assert::false( Validators::isDate('2022-13-20 12:13:45') );
	Assert::false( Validators::isDate('-1-07-20 12:13:45') );
	Assert::false( Validators::isDate('2022-07-20 25:13:45') );
	Assert::false( Validators::isDate('2022-07-20 25:00:01') );
	Assert::false( Validators::isDate('2022-07-20 12:60:45') );
	Assert::false( Validators::isDate('2022-07-20 12:13:61') );
	Assert::false( Validators::isDate('07-20 12:13:45') );
});
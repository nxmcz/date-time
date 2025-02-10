# DateTime
An immutable class to deal with DateTime object used in `Noxem` systems. Works perfectly with [nette/forms](https://github.com/nette/forms) as Date, DateTime form's field. 

**PHP 8.2 ready!!**

[![Testing](https://badgen.net/github/checks/nxmcz/date-time/main?cache=300)](https://github.com/nxmcz/date-time/actions)
[![Coverage Status](https://coveralls.io/repos/github/nxmcz/date-time/badge.svg?branch=main)](https://coveralls.io/github/nxmcz/date-time?branch=main)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fnxmcz%2Fdate-time%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/nxmcz/date-time/main)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/nxmcz/date-time/blob/main/LICENSE)
[![Latest Stable Version](http://poser.pugx.org/nxmcz/date-time/v)](https://packagist.org/packages/nxmcz/date-time)
[![Total Downloads](http://poser.pugx.org/nxmcz/date-time/downloads)](https://packagist.org/packages/nxmcz/date-time)
[![License](http://poser.pugx.org/nxmcz/date-time/license)](https://packagist.org/packages/nxmcz/date-time)

Requirements
------------
This library requires PHP 8.1 or later.

Usage
-----

**Noxem\DateTime\DT**

Basic initialization of DT object. DT object is child of native DateTimeImmutable object with handles for modify date/time parts.

```php
use Noxem\DateTime\DT;
use DateTime as NativeDateTime; 

DT::create('now'); // 2022-08-07 12:00:00
(new DT('now')); // 2022-08-07 12:00:00
DT::create(1659866400); // 2022-08-07 12:00:00 initialize with timestamp

DT::create('2022-08-07 12:00:00'); // 2022-08-07 12:00:00
DT::createFromParts(2022, 8, 7, 12); // 2022-08-07 12:00:00
DT::createFromFormat(); // PHP's native method
DT::createFromInterface(new NativeDateTime()); 
DT::fromUTC("2022-08-07T12:00:00Z"); // 2022-08-07 14:00:00 in Europe/Prague

DT::getOrCreateInstance("2022-08-07 12:00:00"); // 2022-08-07 12:00:00
DT::getOrCreateInstance(DT::create("2022-08-08 12:00:00")); // 2022-08-08 12:00:00

$dt = DT::create('now'); // 2022-08-07 12:00:00
$dt->modify('+5 seconds'); // 2022-08-07 12:00:05
$dt->addDays(1); // 2022-08-08 12:00:00
$dt->subDays(1); // 2022-08-06 12:00:00
$dt->modifyDays(1); // ekvivalent to addDays(1)
```
Library supports casting object into HTML's native input types

```php
use Noxem\DateTime\DT;

$object = DT::create('2022-08-07 12:00:00');
$object->toHtmlDate(); // 2022-08-07
$object->toHtmlMonth(); // 2022-08
$object->toHtmlWeek(); // 2022-W31
$object->toHtmlYear(); // 2022
```

Comparation:
```php
DT::create('2022-08-07 12:00:00')
    ->areEquals(
        DT::create('2022-08-07 12:00:00')
        ->setTimezone('America/New_York')
    ); // FALSE

$ny = DT::create("2020-09-17 07:00:00")->assignTimezone("America/New_York");
$tokyo = DT::create("2020-09-17 20:00:00")->assignTimezone("Asia/Tokyo");
$ny->areEquals($tokyo); // TRUE
```
`isFuture(): bool` We operating with future? 
```php
$dt = DT::create('now');
echo $dt->modify('+1 seconds')->isFuture(); // TRUE
echo $dt->isFuture(); // FALSE
```
**Noxem\DateTime\Difference**

Difference formula can be imagined as `x = a - b`, where `a` is object which calling child method, formula example is `x = $b->difference($a)`
Difference class is accessible with method: 
`DT::create()->difference(DT $suspect)`
```php
$bigger = DT::create('2022-05-20 11:45:00');
$smaller = DT::create('2022-05-13 11:45:00');

$dt = $smaller->difference($bigger);
echo $dt->hours(); // 168.0
echo $dt->days(); // 7
echo $dt->solidWeeks(); // 1
echo $dt->minutes(); // 1440.0
echo $dt->msec(); // 86400000

$dt = $bigger->difference($smaller);
echo $dt->hours(); // -168.0
echo $dt->days(); // -7
echo $dt->solidWeeks(); // -1
echo $dt->minutes(); // -1440.0
echo $dt->msec(); // -86400000
```
Output of class is signed numbers. Where: positive numbers represent future, negative going back to future.

| Sign of number | Example | Description |
|----------------|---------|-------------|
| -              | -5      | Past        |
| +              | +5      | Future      |

Method `withAbsolute()` ignores negative numbers on methods, difference will be always in positive numbers
```php
$first = DT::create('2022-05-20 11:45:00');
$last = DT::create('2022-05-13 11:45:00');

$dt = $first->difference($last);
echo $dt->hours(); // -168.0
echo $dt->withAbsolute()->hours(); // +168.0
```
Method is also immutable.

**Noxem\DateTime\Overlap**

Next method is for compare two objects if overlap or not.

```php
use Noxem\DateTime\Overlapping;

Overlapping::withTouching(
    DT::create('2021-05-06 09:00:00'),
    DT::create('2021-05-06 10:00:00'),
    DT::create('2021-05-06 10:00:00'),
    DT::create('2021-05-06 13:00:00'),
); // FALSE
```
Overlapping class handles only with one method (in future quantities increase). Description of interval overlap statements are presented in table below:

| Step                     | Interval visualisation                    | Result |
|:-------------------------|:------------------------------------------|:-------|
| **BORDERS**              | `            I           I            `   |        |
| After                    | `     ██████ I           I            `   | FALSE  |
| Start touching           | `     ███████I           I            `   | FALSE  |
| Start inside             | `        ████I██         I            `   | TRUE   |
| Inside start touching    | `            I███████████I███████     `   | TRUE   |
| Enclosing start touching | `            I██████     I            `   | TRUE   |
| Enclosing                | `            I    █████  I            `   | TRUE   |
| Enclosing end touching   | `            I       ████I            `   | TRUE   |
| Exact match              | `            I███████████I            `   | TRUE   |
| Inside                   | `        ████I███████████I██████      `   | TRUE   |
| Inside end touching      | `        ████I███████████I            `   | TRUE   |
| End inside               | `            I       ████I████████████`   | TRUE   |
| End touching             | `            I           I████████████`   | FALSE  |
| Before                   | `            I           I  ███████████`  | FALSE  |



Exception
---------
Bad DateTime format throws an exception which is children of `InvalidArgumentException`

```php
use Noxem\DateTime\DT;
use Noxem\DateTime\Exception\BadFormatException;

$dt = DT::create('foo'); // BadFormatException
```

# DateTime
An immutable class to deal with DateTime object used in `Noxem` systems. Works perfectly with [nette/forms](https://github.com/nette/forms) as Date, DateTime form's field. 


<p>
  <a href="https://github.com/nxmcz/date-time/actions"><img src="https://badgen.net/github/checks/nxmcz/date-time/main?cache=300"></a>
  <a href='https://coveralls.io/github/nxmcz/date-time?branch=main'><img src='https://coveralls.io/repos/github/nxmcz/date-time/badge.svg?branch=main' alt='Coverage Status' /></a>
</p>

Requirements
------------
This library requires PHP 8.0 or later.

Usage
-----

**Noxem\DateTime\DT**

Basic initialization of 3 possible input ways:

```php
use Noxem\DateTime\DT;

// NOW

$dt = DT::create('now'); // 2022-08-07 12:00:00
echo $dt->modify('+5 seconds'); // 2022-08-07 12:00:05
echo (new DT('now')); // 2022-08-07 12:00:00

// TIMESTAMP

$dt = DT::create(1659866400); // 2022-08-07 12:00:00 initialize with timestamp
echo $dt->modify('+5 seconds'); // 2022-08-07 12:00:05

// DateParts

$dt = DT::create('2022-08-07 12:00:00'); // 2022-08-07 12:00:00
$dt = DT::createFromParts(2022, 8, 7, 12); // 2022-08-07 12:00:00
```
Also creating `DT` object is possible with 2 more ways:
```php
use Noxem\DateTime\DT;

$dt = DT::createFromParts(2022, 8); // 2022-08-01 00:00:00

// OR

$dt2 = DT::getOrCreateInstance($dt); // $dt2 become $dt
```
Other usefull methods are:

`seconds(): int` Returns same as `getTimestamp()` method on DateTimeInterface class

`millis(): int` Milliseconds after time-dot 20:55:33.123 => 123000 msec

`msec(): int` Time in milliseconds (seconds() multiple by 1000)  

`week(): int` Number of week

`hour(): int` Hour of `DT` object class

`day(): int` Day of `DT` object class

`month(): int` Month of `DT` object class

`year(): int` Year of `DT` object class

`castToMonthInput(): string` Prepare value for `type="month"` HTML native attribute ex: `2022-1`


`areEquals(DT $suspect): bool`
```php
DT::create('2022-08-07 12:00:00')
    ->areEquals(
        DT::create('2022-08-07 12:00:00')
        ->setTimezone('America/New_York')
    ); // FALSE
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


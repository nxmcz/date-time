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
Basic initialization of 3 possible ways:
```php
use Noxem\DateTime\DateTimeImmutable;

// NOW

$dt = DateTimeImmutable::create('now'); // 2022-08-07 12:00:00
echo $dt->modify('+5 seconds'); // 2022-08-07 12:00:05
echo (new DateTimeImmutable('now')); // 2022-08-07 12:00:00

// TIMESTAMP

$dt = DateTimeImmutable::create(1659866400); // 2022-08-07 12:00:00 initialize with timestamp
echo $dt->modify('+5 seconds'); // 2022-08-07 12:00:05

// DateParts

$dt = DateTimeImmutable::create('2022-08-07 12:00:00'); // 2022-08-07 12:00:00
$dt = DateTimeImmutable::createFromParts(2022, 8, 7, 12); // 2022-08-07 12:00:00
```

Other usefull methods are

Is suspected object same as his caller?

`areSame(\DateTimeInterface $suspect)`
```php
DateTimeImmutable::create('2022-08-07 12:00:00')
    ->areSame(
        DateTimeImmutable::create('2022-08-07 12:00:00')
        ->setTimezone('America/New_York')
    ); // FALSE
```
`isFuture()` We operating with future? 
```php
$dt = DateTimeImmutable::create('now');
echo $dt->modify('+1 seconds')->isFuture(); // TRUE
echo $dt->isFuture(); // FALSE
```
`difference(\DateTimeInterface $suspect)` Difference between two objects
```php
$first = DateTimeImmutable::create('2022-05-20 11:45:00');
$last = DateTimeImmutable::create('2022-05-21 11:45:00');
$dt = $first->difference($last); // TRUE
echo $dt->hours(); // 24.0
echo $dt->minutes(); // 1440.0
echo $dt->msec(); // 86400000
```
**Overlap**

Next method is for compare two objects if overlap or not.
```php
use Noxem\DateTime\DateTimeImmutable as DT;

DT::isOverlap(
    DT::create('2021-05-06 09:00:00'),
    DT::create('2021-05-06 10:00:00'),
    DT::create('2021-05-06 10:00:00'),
    DT::create('2021-05-06 13:00:00'),
); // FALSE
```
Step situations are presented in table below:

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
use Noxem\DateTime\DateTimeImmutable;
use Noxem\DateTime\Exception\BadFormatException;

$dt = DateTimeImmutable::create('foo'); // BadFormatException
```


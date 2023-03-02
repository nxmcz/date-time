<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Noxem\DateTime\Difference;
use Noxem\DateTime\DT;
use Noxem\DateTime\DT as DateTime;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class DifferenceTest extends AbstractTestCase
{
	public function test_valid()
	{
		$difference = new Difference(
			$start = DateTime::create('2019-08-01 20:55:59'),
			$end = DateTime::create('2019-08-02 20:55:59')
		);

		Assert::true($start->areEquals($difference->getStart()));

		Assert::same(1564685759000, $difference->getStart()->getMillis());
		Assert::same(1564772159000, $difference->getEnd()->getMillis());

		Assert::true($end->areEquals($difference->getEnd()));
		Assert::type(\DateInterval::class, $difference->getInterval());

		Assert::same(86400000, $difference->getMillis());
		Assert::same(86400, $difference->getSeconds());
		Assert::same(1440.0, $difference->getMinutes());
		Assert::same(24.0, $difference->getHours());

		Assert::false($difference->isActive());

		$live = new Difference(
			DateTime::create('now')->modify('-9 minutes'),
			DateTime::create()->modify('+10 minutes')
		);

		Assert::true($live->isActive());
		Assert::same(540, $live->intervalToNow());
		Assert::same(600, $live->intervalToEnd());
	}

	public function testInvalid()
	{
		Assert::exception(
			fn () => new Difference(
				DateTime::create('2019-08-01 20:55:59'),
				DateTime::create('2019-08-01 20:55:59')->modify('-1 seconds'),
				true
			),
			\InvalidArgumentException::class
		);
	}

    public function testAreEquals(): void
    {
        $difference = new Difference('2023-01-01 00:00:00', '2023-01-01 01:00:00');

        $differenceSecond = $difference;
        Assert::same(true, $difference->areEquals($differenceSecond) );

        $differenceSecond = new Difference('2023-01-01 00:00:00', '2023-01-01 01:00:01');
        Assert::same(false, $difference->areEquals($differenceSecond) );

        $differenceSecond = new Difference('2023-01-01 00:00:01', '2023-01-01 01:00:01');
        Assert::same(false, $difference->areEquals($differenceSecond) );

        $differenceSecond = new Difference('2023-01-01 00:00:01', '2023-01-01 01:00:00');
        Assert::same(false, $difference->areEquals($differenceSecond) );
    }


	public function testDifferenceInLeapYear()
	{
		$beforeHourShift = DT::createFromParts(2022, 10, 29, 15);
		$afterHourShift = DT::createFromParts(2022, 10, 30, 15);
		$difference = $beforeHourShift->difference($afterHourShift);

		Assert::same(1, $difference->getDays());
		Assert::same(86400, $difference->getSeconds());
	}

	/**
	 * @dataProvider providerWeeks
	 */
	public function testWeeks(int $solidNumber, float $weekNumber, string $a, string $b): void
	{
		$first = DT::create($a);

		Assert::same(
			$solidNumber,
			$first
				->difference(DT::create($b))
				->getSolidWeeks()
		);

		Assert::same(
			$weekNumber,
			$first
				->difference(DT::create($b))
				->getWeeks()
		);
	}

	public function providerWeeks(): array
	{
		return [
			[2, 2.0, '2021/07/07', '2021/07/21'],
			[3, 3.0, '2021/07/05', '2021/07/26'],
			[3, 24 / 7, '2021/07/05', '2021/07/29'],
			[-3, -24 / 7, '2021/07/29', '2021/07/05'],
			[-3, -3.0, '2021/07/26', '2021/07/05'],
			[1, 1.0, '2021/06/28', '2021/07/05'],
			[0, 0.0, '2021/06/28', '2021/06/28'],
			[0, 6 / 7, '2021/06/21', '2021/06/27'],
			[0, 1 / 7, '2021/06/23', '2021/06/24'],
			[1, 1 / 7, '2021/06/20', '2021/06/21'],
			[1, 1.0, '2020/12/29', '2021/01/05'],
			[1, 5 / 7, '2020/12/31', '2021/01/05'],
			[2, 13 / 7, '2020/12/23', '2021/01/05'],
			[3, 17 / 7, '2020/12/19', '2021/01/05'],
			[1, 439200 / 604800, '2020/12/29 22:00', '2021/01/04 00:00'],
			[2, 871200 / 604800, '2020/12/24 22:00', '2021/01/04 00:00'],
			[2, 11 / 7, '2020/12/24 00:00', '2021/01/04 00:00'],
			[2, 957600 / 604800, '2020/12/24 03:00', '2021/01/04 05:00'],

			[1, 266400 / 604800, '2022/08/19 03:00', '2022/08/22 05:00'],
			[1, 1.0, '2022/08/19 03:00', '2022/08/26 03:00'],
			[1, 1198799 / 604800, '2022/08/15 03:00', '2022/08/28 23:59:59'],
			[-1, -266400 / 604800, '2022/08/22 05:00', '2022/08/19 03:00'],
			[-1, -1.0, '2022/08/26 03:00', '2022/08/19 03:00'],
			[-1, -1198799 / 604800, '2022/08/28 23:59:59', '2022/08/15 03:00'],

			[0, 1 / 7, '2021/01/02', '2021/01/03'],
			[0, 5 / 7, '2020/12/29', '2021/01/03'],
			[1, 1.0, '2021/12/22', '2021/12/29'],
			[1, 10 / 7, '2021/12/22', '2022/01/01'],
			[-1, -10 / 7, '2022/01/01', '2021/12/22'],
			[1, 11 / 7, '2021/12/22', '2022/01/02'],
			[51, 30931200 / 604800, '2021/01/05', '2021/12/29'],
			[51, 31276800 / 604800, '2021/01/05', '2022/01/02'],

			[139, 139.0, '2019/05/05', '2022/01/02'],
			[-139, -139.0, '2022/01/02', '2019/05/05'],
		];
	}

	/**
	 * @dataProvider providerDays
	 */
	public function testDays(int $number, string $a, string $b): void
	{
		Assert::same(
			$number,
			DT::create($a)
				->difference(DT::create($b))
				->getDays()
		);
	}

	public function providerDays(): array
	{
		return [
			[0, '2021/03/15', '2021/03/15'],
			[1, '2021/03/15', '2021/03/16'],
			[2, '2021/03/15', '2021/03/17'],
			[3, '2021/03/15', '2021/03/18'],
			[4, '2021/03/15', '2021/03/19'],
			[5, '2021/03/15', '2021/03/20'],
			[6, '2021/03/15', '2021/03/21'],
			[10, '2021/03/15', '2021/03/25'],
			[-4, '2021/03/19', '2021/03/15'],
			[-1, '2021/03/16', '2021/03/15'],
			[365, '2021/03/16', '2022/03/16'],
			[366, '2019/03/16', '2020/03/16'], // leap year
			[-366, '2020/03/16', '2019/03/16'], // leap year
		];
	}

	public function testFromReadme(): void
	{
		$bigger = DT::create('2022-05-20 11:45:00');
		$smaller = DT::create('2022-05-13 11:45:00');

		$dt = $smaller->difference($bigger);
		Assert::same(168.0, $dt->getHours());
		Assert::same(7, $dt->getDays());
		Assert::same(1, $dt->getSolidWeeks());
		Assert::same(10080.0, $dt->getMinutes());
		Assert::same(604800000, $dt->getMillis());

		$dt = $bigger->difference($smaller);
		Assert::same(-168.0, $dt->getHours());
		Assert::same(-7, $dt->getDays());
		Assert::same(-1, $dt->getSolidWeeks());
		Assert::same(-10080.0, $dt->getMinutes());
		Assert::same(-604800000, $dt->getMillis());
	}

	public function testWithAbsolute(): void
	{
		$first = DT::create('2022-05-20 11:45:00');
		$last = DT::create('2022-05-13 11:45:00');

		$dt = $first->difference($last);

		Assert::same(-168.0, $dt->getHours());
		Assert::same(-7, $dt->getDays());
		Assert::same(-1, $dt->getSolidWeeks());
		Assert::same(-1.0, $dt->getWeeks());
		Assert::same(-10080.0, $dt->getMinutes());
		Assert::same(-604800000, $dt->getMillis());

		$abs = $dt->withAbsolute();
		Assert::same(168.0, $abs->getHours());
		Assert::same(7, $abs->getDays());
		Assert::same(1, $abs->getSolidWeeks());
		Assert::same(1.0, $abs->getWeeks());
		Assert::same(10080.0, $abs->getMinutes());
		Assert::same(604800000, $abs->getMillis());

		Assert::same(-168.0, $dt->getHours());
		Assert::same(-7, $dt->getDays());
		Assert::same(-1, $dt->getSolidWeeks());
		Assert::same(-1.0, $dt->getWeeks());
		Assert::same(-10080.0, $dt->getMinutes());
		Assert::same(-604800000, $dt->getMillis());
	}

	public function test_get_by_instance(): void
	{
		$first = DT::create('2022-05-20 11:45:00');
		$last = DT::getOrCreateInstance(new \DateTimeImmutable('2022-05-13 11:45:00'));

		$dt = $first->difference($last)->withAbsolute();

		Assert::same(168.0, $dt->getHours());
	}

	public function testToJson(): void
	{
		$first = DT::create('2022-05-20 11:45:00');
		$last = DT::create('2022-05-13 11:45:00');

		$dt = $first->difference($last);
		$serialize = $dt->jsonSerialize();
		Assert::true($dt->getStart()->areEquals($serialize['start']));
		Assert::true($dt->getEnd()->areEquals($serialize['end']));

		Assert::same($serialize['millis'], $dt->getMillis());
		Assert::same($serialize['seconds'], $dt->getSeconds());
		Assert::same($serialize['minutes'], $dt->getMinutes());
		Assert::same($serialize['hours'], $dt->getHours());
		Assert::same($serialize['days'], $dt->getDays());
		Assert::same($serialize['weeks'], $dt->getWeeks());
	}

	public function testTwoObjects()
	{
		Assert::same(
			59,
			DT::create('2022-07-20 15:44:30')
				->difference(DT::create('2022-07-20 15:45:29'))
				->getSeconds()
		);

		Assert::same(
			3.0,
			DT::create('2022-07-20 15:44:30')
				->difference(DT::create('2022-07-20 15:47:30'))
				->getMinutes()
		);

		Assert::true(
			DT::create('2022-07-20 15:44:30')
				->difference(DT::create('2022-07-20 15:47:30'))
				->isValid()
		);

		Assert::exception(
			fn () => DT::create('2022-07-20 15:44:30')
			->difference(DT::create('2022-07-20 15:44:30'), true)
			->isValid(),
			\InvalidArgumentException::class
		);

		Assert::exception(
			fn () => DT::create('2022-07-20 15:44:30')
			->difference(DT::create('2022-07-20 15:44:29'), true)
			->isValid(),
			\InvalidArgumentException::class
		);
	}

	public function testIsDayFlip(): void
	{
		Assert::same(20220501, DT::create('2022-05-01')->getAbsoluteDate());
		Assert::same(20220501, DT::create('2022-5-1')->getAbsoluteDate());
		Assert::same(20220501, DT::create('2022-05-01 12:34:56')->getAbsoluteDate());

		Assert::false(DT::create('2022-05-01 00:00:00')->difference(DT::create('2022-05-01 23:59:59'))->isDayFlip());
		Assert::false(DT::create('2022-05-01 12:00:00')->difference(DT::create('2022-05-01 14:00:00'))->isDayFlip());
		Assert::true(DT::create('2022-05-01 00:00:00')->difference(DT::create('2022-05-02 00:00:00'))->isDayFlip());
		Assert::true(DT::create('2022-05-01 12:00:00')->difference(DT::create('2022-05-02 14:00:00'))->isDayFlip());

		Assert::true(DT::create('2022-05-01 12:00:00')->difference(DT::create('2023-05-01 13:00:00'))->isDayFlip());
		Assert::true(DT::create('2022-05-01 22:00:00')->difference(DT::create('2023-05-02 06:00:00'))->isDayFlip());
	}

	/**
	 * @dataProvider provideClamps
	 */
	public function testClamp(array $borders, array $suspect, array $expected): void
	{
		$difference = new Difference(...$suspect);
		$clamp = $difference->clamp(new Difference(...$borders));

		Assert::same($expected[0], (string) $clamp->getStart());
		Assert::same($expected[1], (string) $clamp->getEnd());
	}

	public function provideClamps(): Generator
	{
		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
		];

		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:00', '2023-01-01 11:00:01'],
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
		];

		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 09:59:59', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
		];

		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 09:59:59', '2023-01-01 11:00:01'],
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
		];

		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:01', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:01', '2023-01-01 11:00:00'],
		];

		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:00', '2023-01-01 10:59:59'],
			['2023-01-01 10:00:00', '2023-01-01 10:59:59'],
		];

		yield [
			['2023-01-01 10:00:00', '2023-01-01 11:00:00'],
			['2023-01-01 10:00:01', '2023-01-01 10:59:59'],
			['2023-01-01 10:00:01', '2023-01-01 10:59:59'],
		];
	}
}

$test = new DifferenceTest();
$test->run();

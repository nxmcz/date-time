<?php

declare(strict_types=1);

namespace Tests\Unit;

use Noxem\DateTime\DT;
use Tester\Assert;
use Tests\Fixtures\TestCase\AbstractTestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class DTTest extends AbstractTestCase
{
	public function setUp()
	{
		parent::setUp(); // TODO: Change the autogenerated stub
		date_default_timezone_set('Europe/Prague');
	}

	/**
	 * @dataProvider providerOfDatesForDayOfWeek
	 */
	public function testGetDayOfWeek($date, $numberOfDay, $iso = TRUE): void
	{
		Assert::same($numberOfDay, DT::create($date)->getDayOfWeek($iso));
	}

	public function providerOfDatesForDayOfWeek(): array
	{
		return [
			['2022-08-29', 1],
			['2022-11-01', 2],
			['2022-09-14', 3],
			['2022-09-15', 4],
			['2022-12-23', 5],
			['2022-08-20', 6],
			['2023-01-01', 7],

			['2022-08-29', 2, FALSE],
			['2022-11-01', 3, FALSE],
			['2022-09-14', 4, FALSE],
			['2022-09-15', 5, FALSE],
			['2022-12-23', 6, FALSE],
			['2022-08-20', 7, FALSE],
			['2023-01-01', 1, FALSE]
		];
	}

	public function testGetDayOfYear(): void
	{
		Assert::same(1, DT::create('2022-01-01')->getDayOfYear());
		Assert::same(365, DT::create('2022-12-31')->getDayOfYear());
		Assert::same(366, DT::create('2020-12-31')->getDayOfYear());
	}

	public function testGetDaysOfYear(): void
	{
		Assert::same(365, DT::createFromParts(2022)->getDaysOfYear());
		Assert::same(366, DT::createFromParts(2020)->getDaysOfYear());
	}

	/**
	 * @dataProvider providerIsLeap
	 */
	public function testIsYearLeap(int $year, bool $isLeap): void
	{
		Assert::same($isLeap, DT::createFromParts(year: $year)->isYearLeap());
	}

	public function providerIsLeap(): array
	{
		return [
			[1595, false],
			[1596, true],
			[1597, false],
			[1598, false],
			[1599, false],
			[1600, true],
			[1601, false],
			[1602, false],
			[1603, false],
			[1604, true],
			[1605, false],
			[1695, false],
			[1696, true],
			[1697, false],
			[1698, false],
			[1699, false],
			[1700, false],
			[1701, false],
			[1702, false],
			[1703, false],
			[1704, true],
			[1705, false],
			[1795, false],
			[1796, true],
			[1797, false],
			[1798, false],
			[1799, false],
			[1800, false],
			[1801, false],
			[1802, false],
			[1803, false],
			[1804, true],
			[1805, false],
			[1895, false],
			[1896, true],
			[1897, false],
			[1898, false],
			[1899, false],
			[1900, false],
			[1901, false],
			[1902, false],
			[1903, false],
			[1904, true],
			[1905, false],
			[1995, false],
			[1996, true],
			[1997, false],
			[1998, false],
			[1999, false],
			[2000, true],
			[2001, false],
			[2002, false],
			[2003, false],
			[2004, true],
			[2005, false],
			[2006, false],
			[2007, false],
			[2008, true],
			[2009, false],
			[2010, false],
			[2011, false],
			[2012, true],
			[2013, false],
			[2014, false],
			[2015, false],
			[2016, true],
			[2017, false],
			[2018, false],
			[2019, false],
			[2020, true],
			[2021, false],
			[2022, false],
		];
	}

	/**
	 * @dataProvider providerOfYearsForMaximumWeek
	 */
	public function testWeeksOfYear(int $year, int $lastWeekNumber): void
	{
		Assert::same($lastWeekNumber, DT::createFromParts(year: $year)->getWeeksOfYear());
	}

	public function providerOfYearsForMaximumWeek(): array {
		return [
			[1900,52],
			[1901,52],
			[1902,52],
			[1903,53],
			[1904,52],
			[1905,52],
			[1906,52],
			[1907,52],
			[1908,53],
			[1909,52],
			[1910,52],
			[1911,52],
			[1912,52],
			[1913,52],
			[1914,53],
			[1915,52],
			[1916,52],
			[1917,52],
			[1918,52],
			[1919,52],
			[1920,53],
			[1921,52],
			[1922,52],
			[1923,52],
			[1924,52],
			[1925,53],
			[1926,52],
			[1927,52],
			[1928,52],
			[1929,52],
			[1930,52],
			[1931,53],
			[1932,52],
			[1933,52],
			[1934,52],
			[1935,52],
			[1936,53],
			[1937,52],
			[1938,52],
			[1939,52],
			[1940,52],
			[1941,52],
			[1942,53],
			[1943,52],
			[1944,52],
			[1945,52],
			[1946,52],
			[1947,52],
			[1948,53],
			[1949,52],
			[1950,52],
			[1951,52],
			[1952,52],
			[1953,53],
			[1954,52],
			[1955,52],
			[1956,52],
			[1957,52],
			[1958,52],
			[1959,53],
			[1960,52],
			[1961,52],
			[1962,52],
			[1963,52],
			[1964,53],
			[1965,52],
			[1966,52],
			[1967,52],
			[1968,52],
			[1969,52],
			[1970,53],
			[1971,52],
			[1972,52],
			[1973,52],
			[1974,52],
			[1975,52],
			[1976,53],
			[1977,52],
			[1978,52],
			[1979,52],
			[1980,52],
			[1981,53],
			[1982,52],
			[1983,52],
			[1984,52],
			[1985,52],
			[1986,52],
			[1987,53],
			[1988,52],
			[1989,52],
			[1990,52],
			[1991,52],
			[1992,53],
			[1993,52],
			[1994,52],
			[1995,52],
			[1996,52],
			[1997,52],
			[1998,53],
			[1999,52],
			[2000,52],
			[2001,52],
			[2002,52],
			[2003,52],
			[2004,53],
			[2005,52],
			[2006,52],
			[2007,52],
			[2008,52],
			[2009,53],
			[2010,52],
			[2011,52],
			[2012,52],
			[2013,52],
			[2014,52],
			[2015,53],
			[2016,52],
			[2017,52],
			[2018,52],
			[2019,52],
			[2020,53],
			[2021,52],
			[2022,52],
			[2023,52],
			[2024,52],
			[2025,52],
			[2026,53],
			[2027,52],
			[2028,52],
			[2029,52],
			[2030,52],
			[2031,52],
			[2032,53],
			[2033,52],
			[2034,52],
			[2035,52],
			[2036,52],
			[2037,53],
			[2038,52],
			[2039,52],
			[2040,52],
			[2041,52],
			[2042,52],
			[2043,53],
			[2044,52],
			[2045,52],
			[2046,52],
			[2047,52],
			[2048,53],
			[2049,52],
			[2050,52],
			[2051,52],
			[2052,52],
			[2053,52],
			[2054,53],
			[2055,52],
			[2056,52],
			[2057,52],
			[2058,52],
			[2059,52],
			[2060,53],
			[2061,52],
			[2062,52],
			[2063,52],
			[2064,52],
			[2065,53],
			[2066,52],
			[2067,52],
			[2068,52],
			[2069,52],
			[2070,52],
			[2071,53],
			[2072,52],
			[2073,52],
			[2074,52],
			[2075,52],
			[2076,53],
			[2077,52],
			[2078,52],
			[2079,52],
			[2080,52],
			[2081,52],
			[2082,53],
			[2083,52],
			[2084,52],
			[2085,52],
			[2086,52],
			[2087,52],
			[2088,53],
			[2089,52],
			[2090,52],
			[2091,52],
			[2092,52],
			[2093,53],
			[2094,52],
			[2095,52],
			[2096,52],
			[2097,52],
			[2098,52],
			[2099,53],
			[2100,52]
		];
	}
}

$test = new DTTest();
$test->run();
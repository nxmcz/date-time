<?php declare(strict_types=1);

namespace Tests\Fixtures\TestCase;

use Tester\TestCase;


abstract class AbstractTestCase extends TestCase
{
	protected static bool $initialized = FALSE;

	public function setUp()
	{
		parent::setUp(); // TODO: Change the autogenerated stub
		if (!static::$initialized) {
			$this->load();
			static::$initialized = TRUE;
		}
	}

	public function load(): void
	{
	}
}
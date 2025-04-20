<?php declare(strict_types=1);

use Tester\Environment;

// Check composer && tester
if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

if (@!include __DIR__ . '/fixtures/AbstractTestCase.php') {
	echo 'Abstract Test Case needs to be included';
	exit(1);
}

if (!function_exists('before')) {
	function before(?Closure $function = NULL)
	{
		static $val;

		if (!func_num_args()) {
			return ($val ? $val() : NULL);
		}

		return $val = $function;
	}
}
if (!function_exists('test')) {
	function test(string $description, Closure $fn): void
	{
		before();
		$fn();
	}
}

date_default_timezone_set('Europe/Prague');

Environment::setup();
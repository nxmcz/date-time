<?php declare(strict_types=1);

namespace Noxem\DateTime\Attributes;

interface Intervalic {
	function getStart();
	function getEnd();
	function getStartTimestamp(): int;
	function getEndTimestamp(): int;
}

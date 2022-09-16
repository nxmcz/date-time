<?php declare(strict_types=1);

namespace Noxem\DateTime\Exception;

abstract class DateTimeException extends \InvalidArgumentException
{
	final public function __construct() {
		parent::__construct();
	}

	public static function create(): self
	{
		return new static();
	}

	public function withMessage(string $message): self {
		$this->message = $message;
		return $this;
	}
}
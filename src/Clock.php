<?php

declare(strict_types=1);

namespace Themosis\Components\Datetime;

use DateTimeImmutable;
use DateTimeZone;

interface Clock {
	public function now(): Clock;

	public function current_time(): DateTimeImmutable;

	public function current_timezone(): DateTimeZone;

	public function default_timezone(): DateTimeZone;

	public function is_past(): bool;

	public function is_future(): bool;
}

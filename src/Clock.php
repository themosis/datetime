<?php

// SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace Themosis\Components\Datetime;

use DateTimeImmutable;
use DateTimeZone;

interface Clock {
	public function now(): Clock;

	public function current_time(): DateTimeImmutable;

	public function timezone(): DateTimeZone;

	public function is_past(): bool;

	public function is_future(): bool;
}

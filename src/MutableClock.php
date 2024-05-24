<?php

// SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace Themosis\Components\Datetime;

use DateTimeImmutable;

interface MutableClock extends Clock {
	public function set_current_time( DateTimeImmutable $current_time ): MutableClock;
}

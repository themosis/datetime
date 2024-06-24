<?php

// SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace Themosis\Components\Datetime;

use DateTimeImmutable;
use DateTimeZone;

final class SystemClock implements MutableClock {
	private DateTimeImmutable $current_time;
	private DateTimeZone $timezone;

	public function __construct(
		?DateTimeImmutable $current_time = null,
		?DateTimeZone $timezone = null,
	) {
		$this->timezone     = $timezone ?: ( $current_time
			? $current_time->getTimezone()
			: new DateTimeZone( 'UTC' ) );
		$this->current_time = $current_time
			? $current_time->setTimezone( $this->timezone )
			: new DateTimeImmutable( 'now', $this->timezone );
	}

	public function current_time(): DateTimeImmutable {
		return $this->current_time;
	}

	public function now(): Clock {
		return new self(
			timezone: $this->timezone,
		);
	}

	public function timezone(): DateTimeZone {
		return $this->timezone;
	}

	public function set_current_time( DateTimeImmutable $current_time ): MutableClock {
		$this->current_time = $current_time;

		return $this;
	}

	public function set_timezone( DateTimeZone $timezone ): MutableClock {
		$this->timezone = $timezone;

		return $this;
	}

	public function is_past(): bool {
		return $this->current_time()->getTimestamp() < $this->now()->current_time()->getTimestamp();
	}

	public function is_future(): bool {
		return $this->current_time()->getTimestamp() > $this->now()->current_time()->getTimestamp();
	}
}

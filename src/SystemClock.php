<?php

// SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace Themosis\Components\Datetime;

use DateTimeImmutable;
use DateTimeZone;

final class SystemClock implements MutableClock
{
    private DateTimeImmutable $currentTime;
    private DateTimeZone $timezone;

    public function __construct(
        ?DateTimeImmutable $currentTime = null,
        ?DateTimeZone $timezone = null,
    ) {
        $this->timezone     = $timezone ?: ( $currentTime
            ? $currentTime->getTimezone()
            : new DateTimeZone('UTC') );
        $this->currentTime = $currentTime
            ? $currentTime->setTimezone($this->timezone)
            : new DateTimeImmutable('now', $this->timezone);
    }

    public function currentTime(): DateTimeImmutable
    {
        return $this->currentTime;
    }

    public function now(): Clock
    {
        return new self(
            timezone: $this->timezone,
        );
    }

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }

    public function setCurrentTime(DateTimeImmutable $currentTime): MutableClock
    {
        $this->currentTime = $currentTime;

        return $this;
    }

    public function setTimezone(DateTimeZone $timezone): MutableClock
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function isPast(): bool
    {
        return $this->currentTime()->getTimestamp() < $this->now()->currentTime()->getTimestamp();
    }

    public function isFuture(): bool
    {
        return $this->currentTime()->getTimestamp() > $this->now()->currentTime()->getTimestamp();
    }
}

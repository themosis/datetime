<?php

// SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace Themosis\Components\Datetime\Tests;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\Test;
use Themosis\Components\Datetime\SystemClock;

final class SystemClockTest extends TestCase
{
    #[Test]
    public function it_can_create_a_default_system_clock_using_current_time(): void
    {
        $clock = new SystemClock();

        $this->assertInstanceOf(DateTimeImmutable::class, $clock->currentTime());
        $this->assertInstanceOf(DateTimeZone::class, $clock->timezone());
        $this->assertSame('UTC', $clock->timezone()->getName());
        $this->assertSame('UTC', $clock->currentTime()->getTimezone()->getName());

        $currentTime = $clock->currentTime();

        $this->assertSame($currentTime->getTimestamp(), $clock->currentTime()->getTimestamp());
    }

    #[Test]
    public function it_can_create_a_system_clock_with_custom_datetime_and_default_utc_timezone(): void
    {
        /** @var DateTimeImmutable $aDate */
        $aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2012-11-23 14:08:56');

        $clock = new SystemClock(currentTime: $aDate);

        $this->assertSame($aDate->getTimestamp(), $clock->currentTime()->getTimestamp());
        $this->assertSame('UTC', $clock->timezone()->getName());
        $this->assertSame('UTC', $clock->currentTime()->getTimezone()->getName());
    }

    #[Test]
    public function it_can_create_a_system_clock_with_custom_datetime_and_leverage_given_datetime_timezone(): void
    {
        /** @var DateTimeImmutable $aDate */
        $aDate = DateTimeImmutable::createFromFormat(
            format: 'Y-m-d H:i:s',
            datetime: '2012-11-23 14:08:56',
            timezone: new DateTimeZone('Europe/Brussels')
        );

        $clock = new SystemClock(currentTime: $aDate);

        $this->assertSame($aDate->getTimestamp(), $clock->currentTime()->getTimestamp());
        $this->assertSame('Europe/Brussels', $clock->timezone()->getName());
        $this->assertSame('Europe/Brussels', $clock->currentTime()->getTimezone()->getName());
    }

    #[Test]
    public function it_can_create_a_system_clock_with_custom_datetime_and_override_given_datetime_timezone(): void
    {
        /** @var DateTimeImmutable $aDate */
        $aDate = DateTimeImmutable::createFromFormat(
            format: 'Y-m-d H:i:s',
            datetime: '2012-11-23 14:08:56',
            timezone: new DateTimeZone('Europe/Paris')
        );

        $clock = new SystemClock(
            currentTime: $aDate,
            timezone: new DateTimeZone('America/New_York'),
        );

        $this->assertSame($aDate->getTimestamp(), $clock->currentTime()->getTimestamp());
        $this->assertSame('Europe/Paris', $aDate->getTimezone()->getName());
        $this->assertSame('America/New_York', $clock->timezone()->getName());
        $this->assertSame('America/New_York', $clock->currentTime()->getTimezone()->getName());
    }

    #[Test]
    public function it_can_evaluate_a_system_clock_datetime_to_be_in_the_past(): void
    {
        /** @var DateTimeImmutable $aDate */
        $aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2012-11-23 14:08:56');

        $clock = new SystemClock(currentTime: $aDate);

        $this->assertTrue($clock->isPast());
    }

    #[Test]
    public function it_can_evaluate_a_system_clock_datetime_to_be_in_the_future(): void
    {
        $clock = new SystemClock();
        $clock->setCurrentTime($clock->currentTime()->add(new DateInterval('P1M')));

        $this->assertTrue($clock->isFuture());
    }

    #[Test]
    public function it_can_change_clock_currentTime_at_runtime(): void
    {
        /** @var DateTimeImmutable $aDate */
        $aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2012-11-23 14:08:56');

        $clock = new SystemClock(
            currentTime: $aDate,
        );

        $this->assertSame($aDate->getTimestamp(), $clock->currentTime()->getTimestamp());

        /** @var DateTimeImmutable $anotherDate */
        $anotherDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2012-12-25 12:00:00');
        $clock->setCurrentTime($anotherDate);

        $this->assertNotSame($aDate->getTimestamp(), $clock->currentTime()->getTimestamp());
        $this->assertSame($anotherDate->getTimestamp(), $clock->currentTime()->getTimestamp());
    }

    #[Test]
    public function it_can_change_clock_timezone_at_runtime(): void
    {
        $aTimezone = new DateTimeZone('Europe/Paris');

        $clock = new SystemClock(
            timezone: $aTimezone,
        );

        $this->assertSame($aTimezone->getName(), $clock->timezone()->getName());

        $anotherTimezone = new DateTimeZone('America/New_York');
        $clock->setTimezone($anotherTimezone);

        $this->assertNotSame($aTimezone->getName(), $clock->timezone()->getName());
        $this->assertSame($anotherTimezone->getName(), $clock->timezone()->getName());
    }
}

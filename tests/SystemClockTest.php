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

final class SystemClockTest extends TestCase {
	#[Test]
	public function it_can_create_a_default_system_clock_using_current_time(): void {
		$clock = new SystemClock();

		$this->assertInstanceOf( DateTimeImmutable::class, $clock->current_time() );
		$this->assertInstanceOf( DateTimeZone::class, $clock->timezone() );
		$this->assertSame( 'UTC', $clock->timezone()->getName() );
		$this->assertSame( 'UTC', $clock->current_time()->getTimezone()->getName() );

		$current_time = $clock->current_time();

		$this->assertSame( $current_time->getTimestamp(), $clock->current_time()->getTimestamp() );
	}

	#[Test]
	public function it_can_create_a_system_clock_with_custom_datetime_and_default_utc_timezone(): void {
		/** @var DateTimeImmutable $a_date */
		$a_date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', '2012-11-23 14:08:56' );

		$clock = new SystemClock( current_time: $a_date );

		$this->assertSame( $a_date->getTimestamp(), $clock->current_time()->getTimestamp() );
		$this->assertSame( 'UTC', $clock->timezone()->getName() );
		$this->assertSame( 'UTC', $clock->current_time()->getTimezone()->getName() );
	}

	#[Test]
	public function it_can_create_a_system_clock_with_custom_datetime_and_leverage_given_datetime_timezone(): void {
		/** @var DateTimeImmutable $a_date */
		$a_date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', '2012-11-23 14:08:56', new DateTimeZone( 'Europe/Brussels' ) );

		$clock = new SystemClock( current_time: $a_date );

		$this->assertSame( $a_date->getTimestamp(), $clock->current_time()->getTimestamp() );
		$this->assertSame( 'Europe/Brussels', $clock->timezone()->getName() );
		$this->assertSame( 'Europe/Brussels', $clock->current_time()->getTimezone()->getName() );
	}

	#[Test]
	public function it_can_create_a_system_clock_with_custom_datetime_and_override_given_datetime_timezone(): void {
		/** @var DateTimeImmutable $a_date */
		$a_date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', '2012-11-23 14:08:56', new DateTimeZone( 'Europe/Paris' ) );

		$clock = new SystemClock(
			current_time: $a_date,
			timezone: new DateTimeZone( 'America/New_York' ),
		);

		$this->assertSame( $a_date->getTimestamp(), $clock->current_time()->getTimestamp() );
		$this->assertSame( 'Europe/Paris', $a_date->getTimezone()->getName() );
		$this->assertSame( 'America/New_York', $clock->timezone()->getName() );
		$this->assertSame( 'America/New_York', $clock->current_time()->getTimezone()->getName() );
	}

	#[Test]
	public function it_can_evaluate_a_system_clock_datetime_to_be_in_the_past(): void {
		/** @var DateTimeImmutable $a_date */
		$a_date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', '2012-11-23 14:08:56' );

		$clock = new SystemClock( current_time: $a_date );

		$this->assertTrue( $clock->is_past() );
	}

	#[Test]
	public function it_can_evaluate_a_system_clock_datetime_to_be_in_the_future(): void {
		$clock = new SystemClock();
		$clock->set_current_time( $clock->current_time()->add( new DateInterval( 'P1M' ) ) );

		$this->assertTrue( $clock->is_future() );
	}

	#[Test]
	public function it_can_change_clock_current_time_at_runtime(): void {
		/** @var DateTimeImmutable $a_date */
		$a_date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', '2012-11-23 14:08:56' );

		$clock = new SystemClock(
			current_time: $a_date,
		);

		$this->assertSame( $a_date->getTimestamp(), $clock->current_time()->getTimestamp() );

		/** @var DateTimeImmutable $another_date */
		$another_date = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', '2012-12-25 12:00:00' );
		$clock->set_current_time( $another_date );

		$this->assertNotSame( $a_date->getTimestamp(), $clock->current_time()->getTimestamp() );
		$this->assertSame( $another_date->getTimestamp(), $clock->current_time()->getTimestamp() );
	}

	#[Test]
	public function it_can_change_clock_timezone_at_runtime(): void {
		$a_timezone = new DateTimeZone( 'Europe/Paris' );

		$clock = new SystemClock(
			timezone: $a_timezone,
		);

		$this->assertSame( $a_timezone->getName(), $clock->timezone()->getName() );

		$another_timezone = new DateTimeZone( 'America/New_York' );
		$clock->set_timezone( $another_timezone );

		$this->assertNotSame( $a_timezone->getName(), $clock->timezone()->getName() );
		$this->assertSame( $another_timezone->getName(), $clock->timezone()->getName() );
	}
}

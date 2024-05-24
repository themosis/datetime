<?php

// SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>
//
// SPDX-License-Identifier: GPL-3.0-or-later

declare(strict_types=1);

namespace Themosis\Components\Datetime\Tests;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\Test;
use Themosis\Components\Datetime\SystemClock;

final class SystemClockTest extends TestCase {
	#[Test]
	public function it_can_create_a_default_system_clock_using_current_time(): void {
		$clock = new SystemClock();

		$this->assertInstanceOf( DateTimeImmutable::class, $clock->current_time() );
		$this->assertInstanceOf( DateTimeZone::class, $clock->current_timezone() );
		$this->assertSame( 'UTC', $clock->current_timezone()->getName() );
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
		$this->assertSame( 'UTC', $clock->current_timezone()->getName() );
		$this->assertSame( 'UTC', $clock->current_time()->getTimezone()->getName() );
	}
}

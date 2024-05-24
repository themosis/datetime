<?php

declare(strict_types=1);

namespace Themosis\Components\Datetime;

use DateTimeImmutable;
use DateTimeZone;
use RuntimeException;

final class SystemClock implements Clock {
	public const DATETIME_FORMAT = 'Y-m-d H:i:s';

	private ?DateTimeImmutable $current_time = null;

	private ?DateTimeZone $current_timezone = null;

	public function current_time(): DateTimeImmutable {
		if ( null === $this->current_time ) {
			$this->current_time = new DateTimeImmutable( 'now', $this->current_timezone() );
		}

		return $this->current_time;
	}

	public function now(): self {
		$self = new self();
		$self->current_time();

		return $self;
	}

	public function current_timezone(): DateTimeZone {
		if ( null === $this->current_timezone ) {
			$this->current_timezone = $this->default_timezone();
		}

		return $this->current_timezone;
	}

	public function default_timezone(): DateTimeZone {
		return new DateTimeZone( 'UTC' );
	}

	public function set_current_time( string $time, ?string $format = null, ?DateTimeZone $timezone = null ): void {
		$datetime = DateTimeImmutable::createFromFormat(
			format: ( $format ?? self::DATETIME_FORMAT ),
			datetime: $time,
			timezone: ( $timezone ?? $this->current_timezone() ),
		);

		if ( false === $datetime ) {
			throw new RuntimeException( 'Invalid current time.' );
		}

		$this->current_time = $datetime;
	}

	public function set_current_timezone( string $timezone ): void {
		$this->current_timezone = new DateTimeZone( $timezone );
	}

	public function is_past(): bool {
		return $this->current_time()->getTimestamp() < $this->now()->current_time()->getTimestamp();
	}

	public function is_future(): bool {
		return $this->current_time()->getTimestamp() > $this->now()->current_time()->getTimestamp();
	}
}

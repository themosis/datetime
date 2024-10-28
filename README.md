<!--
SPDX-FileCopyrightText: 2024 Julien Lambé <julien@themosis.com>

SPDX-License-Identifier: GPL-3.0-or-later
-->

Themosis Datetime
=================

The Themosis datetime component provides a `Clock` interface around PHP date and time functions.

Installation
------------

Install the library using [Composer](https://getcomposer.org/):

```shell
composer require themosis/datetime
```

Usage
-----

The library provides 2 interfaces to deal with date and time:

1. The `Clock` interface
2. The `MutableClock` interface

As the name suggests, everytime the `Clock` interface is referenced, you actually get an immutable clock instance.

The `MutableClock` interface provides an additional method to let you modify the current time.

### Create a clock (now)

The library comes with a default implementation of the `Clock` and `MutableClock` interfaces.

You can create a new clock by using the `SystemClock` class:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$clock = new SystemClock();
```

By default, a clock without any parameters will create a clock with "now" as the current time. It also sets the current timezone to "UTC".

### Get current time

To retrieve the clock current time, use the `currentTime()` method.
The method returns a [DateTimeImmutable](https://www.php.net/manual/class.datetimeimmutable) instance you can work with.

> The currentTime() method is returning the DateTimeImmutable instance with its value generated at instantiation.

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$clock = new SystemClock();
$datetime = $clock->currentTime();
```

### Get timezone

To retrieve the clock timezone, use the `timezone()` method.
The method returns a [DateTimeZone](https://www.php.net/manual/class.datetimezone.php) instance.

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$clock = new SystemClock();
$timezone = $clock->timezone();
```

### Get "now" clock

If you need to retrieve the current "now" time from the clock, use the `now()` method:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:05:42');

$clock = new SystemClock($aDate);
$currentTime = $clock->currentTime();
// 2020-01-01 11:05:42

$now = $clock->now();
```

The above `$now` variable is a new `Clock` instance. To access its value, use the `currentTime()` method.

### Create a custom clock

The clock instance accepts 2 parameters in its constructor:

1. The `currentTime` as a `DateTimeImmutable` value.
2. The `timezone` as a `DateTimeZone` value.

You can specify the clock to represent a specific moment in time like so:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:05:42');

$clock = new SystemClock($aDate);
```

If no timezone is provided as a second parameter, the clock instance will set the timezone to "UTC".

In the case a timezone is specified inside the given `DateTimeImmutable` value, the clock instance will respect and set its timezone accordingly:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:05:42', new DateTimeZone('Europe/Brussels'));

$clock = new SystemClock($aDate);
$clock
    ->timezone()
    ->getName();

// "Europe/Brussels"
```

You can pass a timezone parameter to the clock constructor and it will be used as the clock timezone, overriding the timezone passed to the datetime parameter:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:05:42', new DateTimeZone('Europe/Brussels'));
$aTimezone = new DateTimeZone( 'America/New_York' );

$clock = new SystemClock( $aDate, $aTimezone );
$clock
    ->timezone()
    ->getName();

// "America/New_York"

$clock
    ->currentTime()
    ->getTimezone()
    ->getName();

// "America/New_York"
```

> The given `DateTimeImmutable` instance timezone is also updated with the one provided in the constructor.

### Check clock is in the past

You can check if the clock is *currently* in the past using the `isPast()` method:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:05:42');

$clock = new SystemClock($aDate);
$clock->isPast();

// true
```

### Check clock is in the future

You can check if the clock is *currently* in the future using the `isFuture()` method:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$aDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '3506-04-21 08:23:08');

$clock = new SystemClock( $aDate );
$clock->isFuture();

// true
```

### Change current time at runtime

The `SystemClock` implements the `MutableClock` interface which extends the `Clock` interface.

The `SystemClock` can be used as an immutable and mutable clock.

You can modify the clock current time at runtime using the `setCurrentTime()` method:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$clock = new SystemClock();
$clock->setCurrentTime(
    DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:05:42')
);
```

### Change timezone at runtime

You can modify the clock timezone at runtime using the `setTimezone()` method:

```php
<?php

use Themosis\Components\Datetime\SystemClock;

$clock = new SystemClock();
$clock->setTimezone(new DateTimeZone('Europe/Brussels'));
```


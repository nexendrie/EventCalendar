<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Events;

final readonly class DateChanged
{
    public function __construct(public int $year, public int $month)
    {
    }
}

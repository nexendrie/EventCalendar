<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar;

/**
 * You can load events only from 1 month. If user changes month, handle this by event onDateChange.
 */
interface EventModel
{
    /**
     * exists some event for that day?
     */
    public function isForDate(int $year, int $month, int $day): bool;

    /**
     * return array with events - output is NOT escaped (you can use html)
     */
    public function getForDate(int $year, int $month, int $day): array;
}

<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

use MyTester\Attributes\TestSuite;

#[TestSuite("GoogleData")]
final class GoogleDataTest extends \MyTester\TestCase
{
    public function testEvents(): void
    {
        $date = new \DateTime();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $day = (int) $date->format('j');

        $googleData = new GoogleData();
        $events = $googleData->events;
        $this->assertType('array', $events);
        $this->assertCount(0, $events);
        $this->assertFalse($googleData->isForDate($year, $month, $day));
        $events = $googleData->getForDate($year, $month, $day);
        $this->assertType('array', $events);
        $this->assertCount(0, $events);

        $event = (new GoogleEvent('1'));
        $event->start = $date;
        $event->end = $date;

        $googleData->addEvent($event);
        $events = $googleData->events;
        $this->assertType('array', $events);
        $this->assertCount(1, $events);
        $this->assertTrue($googleData->isForDate($year, $month, $day));
        $events = $googleData->getForDate($year, $month, $day);
        $this->assertType('array', $events);
        $this->assertCount(1, $events);
    }
}

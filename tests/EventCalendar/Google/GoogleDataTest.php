<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

require __DIR__ . '/../../bootstrap.php';

use Tester\Assert;

/**
 * @testCase
 */
final class GoogleDataTest extends \Tester\TestCase
{
    public function testEvents(): void
    {
        $date = new \DateTime();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $day = (int) $date->format('j');

        $googleData = new GoogleData();
        $events = $googleData->events;
        Assert::type('array', $events);
        Assert::count(0, $events);
        Assert::false($googleData->isForDate($year, $month, $day));
        $events = $googleData->getForDate($year, $month, $day);
        Assert::type('array', $events);
        Assert::count(0, $events);

        $event = (new GoogleEvent('1'));
        $event->start = $date;
        $event->end = $date;

        $googleData->addEvent($event);
        $events = $googleData->events;
        Assert::type('array', $events);
        Assert::count(1, $events);
        Assert::true($googleData->isForDate($year, $month, $day));
        $events = $googleData->getForDate($year, $month, $day);
        Assert::type('array', $events);
        Assert::count(1, $events);
    }
}


$testCase = new GoogleDataTest();
$testCase->run();

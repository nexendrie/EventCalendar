<?php
declare(strict_types=1);

namespace EventCalendar\Google;

require __DIR__ . '/../../bootstrap.php';

use Tester\Assert;

final class GoogleDataTest extends \Tester\TestCase
{
    public function testName()
    {
        $googleData = new GoogleData();
        $googleData->name = 'name';
        Assert::same('name', $googleData->name);
    }

    public function testDescription()
    {
        $googleData = new GoogleData();
        $googleData->description = 'description';
        Assert::same('description', $googleData->description);
    }

    public function testEvents()
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

        $event = (new GoogleEvent('1'))
            ->setStart($date)
            ->setEnd($date);

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

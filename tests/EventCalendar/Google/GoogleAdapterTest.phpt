<?php
declare(strict_types=1);

namespace EventCalendar\Google;

require __DIR__ . '/../../bootstrap.php';

use Nette\Caching\Cache;
use Nette\Caching\Storages\MemoryStorage;
use Tester\Assert;

final class GoogleAdapterTest extends \Tester\TestCase
{
    public function testCachedEvents()
    {
        $date = new \DateTime();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $day = (int) $date->format('j');

        $googleAdapter = new GoogleAdapter('1', 'x');
        $googleAdapter->setBoundary($year, $month);
        $googleAdapter->cache = $cache = new Cache(new MemoryStorage());
        $cache->save($year . '-' . $month, new TestGoogleData());

        $googleData = $googleAdapter->loadEvents();
        $events = $googleData->events;
        Assert::type('array', $events);
        Assert::count(1, $events);
        Assert::true($googleData->isForDate($year, $month, $day));
        $events = $googleData->getForDate($year, $month, $day);
        Assert::type('array', $events);
        Assert::count(1, $events);
    }
}


$testCase = new GoogleAdapterTest();
$testCase->run();

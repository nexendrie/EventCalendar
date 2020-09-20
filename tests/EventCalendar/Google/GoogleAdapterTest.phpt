<?php

declare(strict_types=1);

namespace EventCalendar\Google;

require __DIR__ . '/../../bootstrap.php';

use Nette\Caching\Cache;
use Nette\Caching\Storages\MemoryStorage;
use Tester\Assert;

/**
 * @testCase
 */
final class GoogleAdapterTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    public function testCachedEvents()
    {
        $date = new \DateTime();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $day = (int) $date->format('j');

        $googleAdapter = new GoogleAdapter('1', 'x');
        $googleAdapter->setBoundary($year, $month);
        $googleAdapter->cache = $cache = new Cache(new MemoryStorage());
        /** @var TestGoogleData $googleData */
        $googleData = $this->getService(TestGoogleData::class);
        $cache->save($year . '-' . $month, $googleData->getData());

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

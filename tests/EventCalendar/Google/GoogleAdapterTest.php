<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

use MyTester\Attributes\TestSuite;
use Nette\Caching\Cache;
use Nette\Caching\Storages\MemoryStorage;

#[TestSuite("GoogleAdapter")]
final class GoogleAdapterTest extends \MyTester\TestCase
{
    use \MyTester\Bridges\NetteDI\TCompiledContainer;

    public function testCachedEvents(): void
    {
        $date = new \DateTime();
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $day = (int) $date->format('j');

        $googleAdapter = new GoogleAdapter('1', 'x');
        $googleAdapter->setBoundary($year, $month);
        $googleAdapter->cache = $cache = new Cache(new MemoryStorage());
        $googleData = $this->getService(TestGoogleData::class);
        $cache->save($year . '-' . $month, $googleData->getData());

        $googleData = $googleAdapter->loadEvents();
        $events = $googleData->events;
        $this->assertType('array', $events);
        $this->assertCount(1, $events);
        $this->assertTrue($googleData->isForDate($year, $month, $day));
        $events = $googleData->getForDate($year, $month, $day);
        $this->assertType('array', $events);
        $this->assertCount(1, $events);
    }
}

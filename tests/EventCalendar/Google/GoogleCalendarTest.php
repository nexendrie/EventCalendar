<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

use MyTester\Attributes\BeforeTest;
use MyTester\Attributes\TestSuite;
use Nette\Caching\Cache;
use Nette\Caching\Storages\MemoryStorage;
use Nexendrie\EventCalendar\DomQuery;

#[TestSuite("GoogleCalendar")]
final class GoogleCalendarTest extends \MyTester\TestCase
{
    use \MyTester\Bridges\NetteApplication\TComponent;
    use \MyTester\Bridges\NetteDI\TCompiledContainer;

    private GoogleCalendar $calendar;

    #[BeforeTest]
    public function prepareComponent(): void
    {
        if (!isset($this->calendar)) {
            $this->calendar = new GoogleCalendar();
            $this->calendar->googleAdapter = $googleAdapter = new GoogleAdapter('1', 'x');
            $googleAdapter->cache = $cache = new Cache(new MemoryStorage());
            $date = new \DateTime();
            $year = (int) $date->format('Y');
            $month = (int) $date->format('n');
            /** @var TestGoogleData $googleData */
            $googleData = $this->getService(TestGoogleData::class);
            $cache->save($year . '-' . $month, $googleData->getData());
        }
        $this->attachToPresenter($this->calendar);
    }

    public function testStructure(): void
    {
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfValidDays = count($dom->find('.ec-validDay'));
        $noOfEmptyDays = count($dom->find('.ec-empty'));
        $this->assertSame((int) date('t'), $noOfValidDays);
        $this->assertTrue($noOfEmptyDays > 0); // 4
    }

    public function testMaxLenOfWday(): void
    {
        $this->calendar->firstDay = GoogleCalendar::FIRST_MONDAY;
        $this->calendar->options[GoogleCalendar::OPT_WDAY_MAX_LEN] = 3;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = (string) $wednesElem[2]->asXML();
        $this->assertSame('Wed', strip_tags($wednesdayName));
    }

    /*public function testDisabledTopNav(): void
    {
        $this->calendar->options[GoogleCalendar::OPT_SHOW_TOP_NAV] = false;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $this->assertFalse($dom->has('.ec-monthTable a'));
    }*/

    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        return (string) ob_get_clean();
    }
}

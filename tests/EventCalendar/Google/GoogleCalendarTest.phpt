<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

require __DIR__ . '/../../bootstrap.php';

use Nette\Caching\Cache;
use Nette\Caching\Storages\MemoryStorage;
use Tester\DomQuery;
use Tester\Assert;

/**
 * @testCase
 */
final class GoogleCalendarTest extends \Tester\TestCase
{
    use \Testbench\TComponent;
    use \Testbench\TCompiledContainer;

    private GoogleCalendar $calendar;
    
    protected function setUp()
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
    
    public function testStructure()
    {
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfValidDays = count($dom->find('.ec-validDay'));
        $noOfEmptyDays = count($dom->find('.ec-empty'));
        Assert::same((int) date('t'), $noOfValidDays);
        Assert::true($noOfEmptyDays > 0); // 4
    }
    
    public function testMaxLenOfWday()
    {
        $this->calendar->firstDay = GoogleCalendar::FIRST_MONDAY;
        $this->calendar->options[GoogleCalendar::OPT_WDAY_MAX_LEN] = 3;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = $wednesElem[2]->asXML();
        Assert::same('Wed', strip_tags($wednesdayName));
    }

    /*public function testDisabledTopNav()
    {
        $this->calendar->options[GoogleCalendar::OPT_SHOW_TOP_NAV] = false;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        Assert::false($dom->has('.ec-monthTable a'));
    }*/
    
    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        return ob_get_clean();
    }
}


$testCase = new GoogleCalendarTest();
$testCase->run();

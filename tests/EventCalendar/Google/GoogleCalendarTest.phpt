<?php
declare(strict_types=1);

namespace EventCalendar\Google;

require __DIR__ . '/../../bootstrap.php';

use Nette\Caching\Cache;
use Nette\Caching\Storages\MemoryStorage;
use Tester\DomQuery;
use Tester\Assert;

/**
 * @skip
 */
final class GoogleCalendarTest extends \Tester\TestCase
{
    use \Testbench\TComponent;
    
    /**
     * @var GoogleCalendar
     */
    private $calendar;
    
    protected function setUp()
    {
        if (is_null($this->calendar)) {
            $this->calendar = new GoogleCalendar();
            $this->calendar->setTranslator(new class implements \Nette\Localization\ITranslator
            {
                public function translate($message, ...$parameters): string
                {
                    return $message;
                }
            });
            $this->calendar->googleAdapter = $googleAdapter = new GoogleAdapter('1', 'x');
            $googleAdapter->cache = $cache = new Cache(new MemoryStorage());
            $date = new \DateTime();
            $year = (int) $date->format('Y');
            $month = (int) $date->format('n');
            $cache->save($year . '-' . $month, new TestGoogleData());
        }
        $this->attachToPresenter($this->calendar);
    }
    
    public function testStructure()
    {
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfValidDays = count($dom->find('.ec-validDay'));
        $noOfEmptyDays = count($dom->find('.ec-empty'));
        Assert::true($noOfValidDays === 31 && $noOfEmptyDays === 4);
    }
    
    public function testMaxLenOfWday()
    {
        $this->calendar->setFirstDay(GoogleCalendar::FIRST_MONDAY);
        $this->calendar->setOptions([GoogleCalendar::OPT_WDAY_MAX_LEN => 3]);
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = $wednesElem[2]->asXML();
        Assert::same('Wed', strip_tags($wednesdayName));
    }
    
    public function testDisabledTopNav()
    {
        $this->calendar->setOptions([GoogleCalendar::OPT_SHOW_TOP_NAV => false]);
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        Assert::true(!$dom->has('.ec-monthTable a'));
    }
    
    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        $html = ob_get_clean();
        return $html;
    }
}


$testCase = new GoogleCalendarTest();
$testCase->run();

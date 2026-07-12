<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Simple;

use MyTester\Attributes\BeforeTest;
use MyTester\Attributes\TestSuite;
use Nexendrie\EventCalendar\DomQuery;

#[TestSuite("SimpleCalendar")]
final class SimpleCalendarTest extends \MyTester\TestCase
{
    use \MyTester\Bridges\NetteApplication\TComponent;

    private SimpleCalendar $calendar;

    #[BeforeTest]
    public function prepareComponent(): void
    {
        if (!isset($this->calendar)) {
            $this->calendar = new SimpleCalendar();
        }
        $this->attachToPresenter($this->calendar);
    }

    public function testBasic(): void
    {
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $this->assertTrue($dom->has('.ec-monthTable'));
    }

    /**
     * Check if the month name is called January
     */
    public function testEnglishMonthName(): void
    {
        $this->calendar->year = 2013;
        $this->calendar->month = 1;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $elem = $dom->find('caption');
        $this->assertContains('January', (string) $elem[0]->asXML());
    }

    public function testWrongLang(): void
    {
        $this->calendar->language = 'esperanto';
        $this->assertThrowsException(
            function () {
                $this->calendar->render();
            },
            \LogicException::class
        );
    }

    /**
     * Check if the first day of week is called "Pondělí"
     */
    public function testCzechCalendar(): void
    {
        $this->calendar->firstDay = SimpleCalendar::FIRST_MONDAY;
        $this->calendar->language = SimpleCalendar::LANG_CZ;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $elem = $dom->find('.ec-monthTable th');
        $mondayName = (string) $elem[0]->asXML();
        $this->assertSame('Pondělí', strip_tags($mondayName));
    }

    public function testDisabledBottomNav(): void
    {
        $this->calendar->options[SimpleCalendar::OPT_SHOW_BOTTOM_NAV] = false;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $this->assertFalse($dom->has('.ec-bottomNavigation'));
    }

    /**
     * Check if the german calendar starting on Monday has wday truncated to three chars
     */
    public function testMaxLenOfWday(): void
    {
        $this->calendar->language = SimpleCalendar::LANG_DE;
        $this->calendar->firstDay = SimpleCalendar::FIRST_MONDAY;
        $this->calendar->options[SimpleCalendar::OPT_WDAY_MAX_LEN] = 3;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = (string) $wednesElem[2]->asXML();
        $this->assertSame('Mit', strip_tags($wednesdayName));
    }

    public function testEvent(): void
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->events = new TestEvent();
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfEvents = count($dom->find('.ec-event'));
        $this->assertSame(2, $noOfEvents);
    }

    public function testEventPosition(): void
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->events = new TestEvent();
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $dayElems = $dom->find('.ec-eventDay .ec-dayOfEvents');
        $day = (int) strip_tags((string) $dayElems[0]->asXML());
        $this->assertTrue($dom->has('.ec-eventDay .ec-eventBox') && $day === 2);
    }

    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        return (string) ob_get_clean();
    }
}

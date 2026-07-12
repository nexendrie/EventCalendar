<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Simple;

use Konecnyjakub\EventDispatcher\AutoListenerProvider;
use Konecnyjakub\EventDispatcher\EventDispatcher;
use MyTester\Attributes\BeforeTest;
use MyTester\Attributes\TestSuite;
use Nexendrie\EventCalendar\DomQuery;
use Nexendrie\EventCalendar\Events\DateChanged;

#[TestSuite("EventCalendar")]
final class EventCalendarTest extends \MyTester\TestCase
{
    use \MyTester\Bridges\NetteApplication\TComponent;

    private EventCalendar $calendar;

    /** @var DateChanged[] */
    private array $events = [];

    #[BeforeTest]
    public function prepareComponent(): void
    {
        if (!isset($this->calendar)) {
            $listenerProvider = new AutoListenerProvider();
            $listenerProvider->addListener(function (DateChanged $event): void {
                $this->events[] = $event;
            });
            $eventDispatcher = new EventDispatcher($listenerProvider);
            $this->calendar = new EventCalendar($eventDispatcher);
        }
        $this->attachToPresenter($this->calendar);
        $this->events = [];
    }

    public function testStructure(): void
    {
        $this->calendar->year = 2013;
        $this->calendar->month = 1;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfValidDays = count($dom->find('.ec-validDay'));
        $this->assertSame(31, $noOfValidDays);
        $noOfEmptyDays = count($dom->find('.ec-empty'));
        $this->assertSame(4, $noOfEmptyDays);
    }

    public function testMaxLenOfWday(): void
    {
        $this->calendar->firstDay = EventCalendar::FIRST_MONDAY;
        $this->calendar->options[EventCalendar::OPT_WDAY_MAX_LEN] = 3;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = (string) $wednesElem[2]->asXML();
        $this->assertSame('Wed', strip_tags($wednesdayName));
    }

    public function testDisabledTopNav(): void
    {
        $this->calendar->options[EventCalendar::OPT_SHOW_TOP_NAV] = false;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $this->assertFalse($dom->has('.ec-monthTable a'));
    }

    public function testTexy(): void
    {
        $this->calendar->year = 2012;
        $this->calendar->month = 2;
        $this->calendar->events = new TestEvent();
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $events = $dom->find('.ec-event');
        $text = (string) $events[0]->asXML();
        $texyOn = class_exists(\Texy::class) && str_contains($text, 'Custom event with <strong>bold</strong>');
        $texyOff = !class_exists(\Texy::class) && str_contains($text, 'Custom event with **bold** text');
        $this->assertTrue($texyOn || $texyOff);
    }

    public function testEvent(): void
    {
        $this->calendar->year = 2026;
        $this->calendar->month = 3;
        $this->renderAndReturnHtml();
        $this->assertCount(1, $this->events);
        $this->assertType(DateChanged::class, $this->events[0]);
        $this->assertSame(2026, $this->events[0]->year);
        $this->assertSame(3, $this->events[0]->month);
    }

    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        return (string) ob_get_clean();
    }
}

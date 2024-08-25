<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Simple;

require __DIR__ . '/../../bootstrap.php';

use Tester\DomQuery;
use Tester\Assert;

/**
 * @testCase
 */
final class EventCalendarTest extends \Tester\TestCase
{
    use \Testbench\TComponent;

    private EventCalendar $calendar;

    protected function setUp(): void
    {
        if (!isset($this->calendar)) {
            $this->calendar = new EventCalendar();
        }
        $this->attachToPresenter($this->calendar);
    }

    public function testStructure(): void
    {
        $this->calendar->year = 2013;
        $this->calendar->month = 1;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $noOfValidDays = count($dom->find('.ec-validDay'));
        Assert::same(31, $noOfValidDays);
        $noOfEmptyDays = count($dom->find('.ec-empty'));
        Assert::same(4, $noOfEmptyDays);
    }

    public function testMaxLenOfWday(): void
    {
        $this->calendar->firstDay = EventCalendar::FIRST_MONDAY;
        $this->calendar->options[EventCalendar::OPT_WDAY_MAX_LEN] = 3;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        $wednesElem = $dom->find('.ec-monthTable th');
        $wednesdayName = (string) $wednesElem[2]->asXML();
        Assert::same('Wed', strip_tags($wednesdayName));
    }

    public function testDisabledTopNav(): void
    {
        $this->calendar->options[EventCalendar::OPT_SHOW_TOP_NAV] = false;
        $html = $this->renderAndReturnHtml();
        $dom = DomQuery::fromHtml($html);
        Assert::false($dom->has('.ec-monthTable a'));
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
        $texyOn = class_exists(\Texy::class) && strpos($text, 'Custom event with <strong>bold</strong>') !== false;
        $texyOff = !class_exists(\Texy::class) && strpos($text, 'Custom event with **bold** text') !== false;
        Assert::true($texyOn || $texyOff);
    }

    private function renderAndReturnHtml(): string
    {
        ob_start();
        $this->calendar->render();
        return (string) ob_get_clean();
    }
}


$testCase = new EventCalendarTest();
$testCase->run();

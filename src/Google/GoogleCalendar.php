<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

use Nexendrie\EventCalendar\AbstractCalendar;

/**
 * Integration with events from Google Calendar
 *
 * Experimental
 */
final class GoogleCalendar extends AbstractCalendar
{
    /**
     * show link to event in Google Calendar, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_LINK = 'showEventLink';
    /**
     * show location if it is set, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_LOCATION = 'showEventLocation';
    /**
     * show description of event if it is set, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_DESCRIPTION = 'showEventDescription';
    /**
     * show start date of event, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_START = 'showEventStart';
    /**
     * show end date of event, default is <b>true</b>
     */
    public const OPT_SHOW_EVENT_END = 'showEventEnd';
    /**
     * Datetime format for start and end date, default is <b>F j, Y, g:i a</b>
     */
    public const OPT_EVENT_DATEFORMAT = 'eventDateformat';

    public GoogleAdapter $googleAdapter;

    public function __construct()
    {
        $this->options[self::OPT_SHOW_EVENT_LINK] = true;
        $this->options[self::OPT_SHOW_EVENT_LOCATION] = true;
        $this->options[self::OPT_SHOW_EVENT_DESCRIPTION] = true;
        $this->options[self::OPT_SHOW_EVENT_START] = true;
        $this->options[self::OPT_SHOW_EVENT_END] = true;
        $this->options[self::OPT_EVENT_DATEFORMAT] = 'F j, Y, g:i a';
    }

    protected function getTemplateFile(): string
    {
        return __DIR__ . '/GoogleCalendar.latte';
    }

    /**
     * @throws GoogleApiException
     */
    public function render(): void
    {
        $this->prepareDate();
        /** @var int $year */
        $year = $this->year;
        /** @var int $month */
        $month = $this->month;
        $this->googleAdapter->setBoundary($year, $month);
        $this->events = $this->googleAdapter->loadEvents();
        parent::render();
    }
}

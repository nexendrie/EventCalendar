<?php

declare(strict_types=1);

namespace EventCalendar\Google;

use EventCalendar\AbstractCalendar;

/**
 * Integration with events from Google Calendar
 *
 * Experimental
 */
final class GoogleCalendar extends AbstractCalendar
{
    /**
     * Show top navigation for changing months, default <b>true</b>
     */

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
        $this->options[static::OPT_SHOW_EVENT_LINK] = true;
        $this->options[static::OPT_SHOW_EVENT_LOCATION] = true;
        $this->options[static::OPT_SHOW_EVENT_DESCRIPTION] = true;
        $this->options[static::OPT_SHOW_EVENT_START] = true;
        $this->options[static::OPT_SHOW_EVENT_END] = true;
        $this->options[static::OPT_EVENT_DATEFORMAT] = 'F j, Y, g:i a';
    }
    
    protected function getTemplateFile(): string
    {
        return __DIR__ . '/GoogleCalendar.latte';
    }

    /**
     * @throws \EventCalendar\Google\GoogleApiException
     */
    public function render(): void
    {
        $this->prepareDate();
        /** @var int $year */
        $year = $this->year;
        /** @var int $month */
        $month = $this->month;
        $this->googleAdapter->setBoundary($year, $month);
        try {
            $this->events = $this->googleAdapter->loadEvents();
        } catch (GoogleApiException $e) {
            throw $e;
        }
        parent::render();
    }
}

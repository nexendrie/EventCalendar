<?php
declare(strict_types=1);

namespace EventCalendar\Google;

use EventCalendar\BasicCalendar;

/**
 * Integration with events from Google Calendar
 *
 * Experimental
 *
 * @todo Allow mix of events from Google Calendar with custom events
 */
class GoogleCalendar extends BasicCalendar
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
     * Do not set events directly, use GoogleAdapter. Mix of events from Google with customs events is not implemented yet.
     * @throws \LogicException
     */
    public function setEvents(\EventCalendar\IEventModel $events): void
    {
        throw new \LogicException('Do not set events directly, use GoogAdapter.');
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

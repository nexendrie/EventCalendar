<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

/**
 * Represents single event from Google Calendar
 */
final class GoogleEvent
{
    use \Nette\SmartObject;

    public string $status;
    public string $htmlLink;
    public \DateTime $created;
    public \DateTime $updated;
    public string $summary;
    public ?string $location = null;
    public ?string $description = null;
    public string $creator = '';
    public \DateTime $start;
    public \DateTime $end;

    public function __construct(public string $id)
    {
    }
}

<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

/**
 * Represents single event from Google Calendar
 *
 * @property-read string $id
 * @property-read string $creator
 */
final class GoogleEvent
{
    use \Nette\SmartObject;

    private string $id;
    public string $status;
    public string $htmlLink;
    public \DateTime $created;
    public \DateTime $updated;
    public string $summary;
    public ?string $location = null;
    public ?string $description = null;
    private string $creator = '';
    public \DateTime $start;
    public \DateTime $end;
    
    public function __construct(string $id)
    {
        $this->id = $id;
    }
    
    public function getId(): string
    {
        return $this->id;
    }

    public function getCreator(): string
    {
        return $this->creator;
    }
}

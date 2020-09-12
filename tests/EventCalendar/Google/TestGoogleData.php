<?php
declare(strict_types=1);

namespace EventCalendar\Google;

class TestGoogleData extends GoogleData
{
    public function __construct()
    {
        $this->name = 'name';
        $this->description = 'description';
        $events = [];
        $event1 = (new GoogleEvent('1'));
        $event1->status = 'status';
        $event1->summary = 'summary';
        $event1->created = new \DateTime();
        $event1->updated = new \DateTime();
        $event1->htmlLink = 'link';
        $event1->start = new \DateTime();
        $event1->end = new \DateTime();
        $event1->description = 'description';
        $event1->location = 'location';
        $events[] = $event1;
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }
}

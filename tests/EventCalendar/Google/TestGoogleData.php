<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

final class TestGoogleData
{
    public function getData(): GoogleData
    {
        $data = new GoogleData();
        $data->name = 'name';
        $data->description = 'description';
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
            $data->addEvent($event);
        }
        return $data;
    }
}

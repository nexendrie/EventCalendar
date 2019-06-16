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
        $events[] = (new GoogleEvent('1'))
            ->setStatus('status')
            ->setSummary('summary')
            ->setCreated(new \DateTime())
            ->setUpdated(new \DateTime())
            ->setHtmlLink('link')
            ->setStart(new \DateTime())
            ->setEnd(new \DateTime())
            ->setDescription('description')
            ->setLocation('location');
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }
}

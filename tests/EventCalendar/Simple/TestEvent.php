<?php

namespace EventCalendar\Simple;

use EventCalendar\IEventModel;

class TestEvent implements IEventModel {
    
    private $events = [];
    
    public function __construct() {
        $this->events['2012-02-02'] = ['Custom event with **bold** text', 'Another event'];
    }
    
    public function getForDate($year, $month, $day) {
        return $this->events[$this->formatDate($year, $month, $day)];
    }
    
    public function isForDate($year, $month, $day) {
        return array_key_exists($this->formatDate($year, $month, $day), $this->events);
    }
    
    private function formatDate($year, $month, $day) {
        return sprintf('%d-%02d-%02d', $year, $month, $day);
    }
    
}

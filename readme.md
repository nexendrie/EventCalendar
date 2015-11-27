EventCalendar
============

- add-on component for Nette framework - https://nette.org/
- enable displaying various events in calendar
- provide methods for localisation & customization
- you can also use html and Texy! in your event texts
- https://addons.nette.org/jaroslav-kubicek/event-calendar

Installing
============

Install component to your project via Composer:

    "require": {
        ...
        "jaroslav-kubicek/event-calendar": "0.2.1"
    }

Quick start
============

Add to your code (in presenter/control):

    protected function createComponentCalendar() {
        $cal = new EventCalendar\Simple\SimpleCalendar();
        return $cal;
    }

and in template:

    {control calendar}

EventCalendar
============

This is an add-on component for [Nette framework] (https://nette.org/) which enables displaying various events in calendar. It provides methods for localisation & customization. You can also use html and Texy! in your event texts.

Installation
============

The best way to install it is via Composer. Just add konecnyjakub/event-calendar to your dependencies. Latest stable version does not work with latest Nette 2.3+ so you have to use current master for now.

Quick start
============

Add to your code (in presenter/control):

    protected function createComponentCalendar() {
        $cal = new EventCalendar\Simple\SimpleCalendar();
        return $cal;
    }

and in template:

    {control calendar}

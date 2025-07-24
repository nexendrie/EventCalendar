Version 0.8.0-dev
- raised minimal version of PHP to 8.1
- BC break: made method GoogleData::getEvents protected

Version 0.7.0
- fixed default value for option wdayMaxLen
- renamed package to nexendrie/event-calendar
- BC break: removed BasicCalendar
- BC break: changed namespace to Nexendrie\EventCalendar
- made GoogleEvent::$id and GoogleEvent::$creator writable
- compatibility with PHP 8.0

Version 0.6.1
- initialized AbstractCalender::$onDateChange
- AbstractCalendar::render() now triggers event onDateChange

Version 0.6.0
- raised minimal version of PHP to 7.4
- made most properties readable, removed most getters and setters
- BC break: BasicCalendar (and its descendants) uses translator registered to Latte
- deprecated SimpleCalendar and BasicCalendar
- allowed custom events in GoogleCalendar
- BC break: translations use message ids
- BC break: day and month names are no longer automatically truncated in BasicCalendar
- BC break: marked most classes as final

Version 0.5.0
- raised minimal version of PHP to 7.3
- renamed namespace EventCalendar\Goog to EventCalendar\Google, renamed classes in it to Google*, renamed property GoogCalendar::$googAdapter to $googleAdapter
- re-added support for Latte 2.5

Version 0.4.0
- updated for Nette 3, dropped support for older versions

Version 0.3.1
- fixed type of year and month for non-default values

Version 0.3.0
- raised minimal version of PHP to 7.1
- updated for Nette 2.4, dropped support for older versions
- fixed Composer autoloading

Version 0.2.2
- added experimental integration with Google Calendar

Version 0.2.1
- moved IEventModel to namespace EventCalendar

Version 0.2.0
- text of event is not escaped
- fixed a bug with switching months
- fix if method setEvents() wasn't called
- added SimpleCalendar
- added Texy support for EventCalendar
- added wrapper for month and year added & test for EventCalendar
- added constants for options

Version 0.1.0
- first version

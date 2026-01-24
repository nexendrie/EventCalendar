Event Calendar
==============

This is an add-on component for Nette framework which enables displaying various events in calendar. It provides methods for localisation & customization. You can also use html and Texy! in your event texts.

Links
-----

Primary repository: https://gitlab.com/nexendrie/EventCalendar
GitHub repository: https://github.com/nexendrie/EventCalendar
Packagist: https://packagist.org/packages/nexendrie/event-calendar

Installation
------------
The best way to install it is via Composer. Just add **nexendrie/event-calendar** to your dependencies.

Quick start
-----------

Add to your code (in presenter/control):

```php
<?php

declare(strict_types=1);

use Nexendrie\EventCalendar\Simple\SimpleCalendar;

class MyPresenter extends \Nette\Application\UI\Presenter
{
    protected function createComponentCalendar(): SimpleCalendar {
        $cal = new SimpleCalendar();
        return $cal;
    }
} 
```

and in template:

```latte
    {control calendar}
```

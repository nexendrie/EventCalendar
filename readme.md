EventCalendar
============

[![Total Downloads](https://poser.pugx.org/nexendrie/event-calendar/downloads)](https://packagist.org/packages/nexendrie/event-calendar)  [![Latest Stable Version](https://poser.pugx.org/nexendrie/event-calendar/v/stable)](https://gitlab.com/nexendrie/EventCalendar/-/releases) [![Build Status](https://gitlab.com/nexendrie/EventCalendar/badges/master/pipeline.svg?ignore_skipped=true)](https://gitlab.com/nexendrie/EventCalendar/-/commits/master) [![Code Coverage](https://gitlab.com/nexendrie/EventCalendar/badges/master/coverage.svg)](https://gitlab.com/nexendrie/EventCalendar/-/commits/master)

This is an add-on component for Nette framework which enables displaying various events in calendar. It provides methods for localisation & customization. You can also use html and Texy! in your event texts.

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

EventCalendar
============

[![Total Downloads](https://poser.pugx.org/nexendrie/event-calendar/downloads)](https://packagist.org/packages/nexendrie/event-calendar)  [![Latest Stable Version](https://poser.pugx.org/nexendrie/event-calendar/v/stable)](https://packagist.org/packages/nexendrie/event-calendar) [![Latest Unstable Version](https://poser.pugx.org/nexendrie/event-calendar/v/unstable)](https://packagist.org/packages/nexendrie/event-calendar) [![Build Status](https://travis-ci.com/nexendrie/EventCalendar.svg?branch=master)](https://travis-ci.com/nexendrie/EventCalendar) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nexendrie/EventCalendar/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nexendrie/EventCalendar/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/nexendrie/EventCalendar/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nexendrie/EventCalendar/?branch=master)

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

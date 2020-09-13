<?php

declare(strict_types=1);

namespace EventCalendar;

abstract class BasicCalendar extends AbstractCalendar
{

    public function render(): void
    {
        $this->template->wdays = $this->getWdays();
        $this->template->monthNames = $this->getMonthNames();
        parent::render();
    }
    
    protected function getWdays(): array
    {
        $wdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        if ($this->firstDay === self::FIRST_MONDAY) {
            array_push($wdays, array_shift($wdays));
        }
        return $this->truncateWdays($wdays);
    }
    
    protected function getMonthNames(): array
    {
        $month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return $month;
    }
}

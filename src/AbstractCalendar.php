<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar;

use Nette\Application\UI;
use Nette\Utils\Arrays;

/**
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 * @method void onDateChange(int $year, int $month)
 */
abstract class AbstractCalendar extends UI\Control
{
    public const FIRST_SUNDAY = 0, FIRST_MONDAY = 1;

    /**
     * Show top navigation for changing months, default <b>true</b>
     */
    public const OPT_SHOW_TOP_NAV = 'showTopNav';
    /**
     * Show bottom navigation for changing months, default <b>true</b>
     */
    public const OPT_SHOW_BOTTOM_NAV = 'showBottomNav';
    /**
     * maximum length of wday names, by default, full name is used (<b>null</b>)
     */
    public const OPT_WDAY_MAX_LEN = 'wdayMaxLen';

    /**
     * @persistent
     */
    public ?int $year = null;

    /**
     * @persistent
     */
    public ?int $month = null;

    /**
     * @var callable[]
     */
    public array $onDateChange = [];

    public int $firstDay = self::FIRST_SUNDAY;

    public IEventModel $events;

    public array $options = [
        self::OPT_SHOW_TOP_NAV => true,
        self::OPT_SHOW_BOTTOM_NAV => true,
        self::OPT_WDAY_MAX_LEN => PHP_INT_MAX,
    ];

    abstract protected function getTemplateFile(): string;

    /**
     * changes current month and invokes onDateChange event
     */
    public function handleChangeMonth(): void
    {
        $this->onDateChange($this->year, $this->month);
        if ($this->presenter->isAjax()) {
            $this->redrawControl('ecCalendar');
        } else {
            $this->redirect('this');
        }
    }

    public function render(): void
    {
        $this->template->setFile($this->getTemplateFile());

        $this->prepareDate();

        /** @var int $year */
        $year = $this->year;
        /** @var int $month */
        $month = $this->month;
        $this->onDateChange($year, $month);
        $dateInfo = [];
        $dateInfo['year'] = $year; // current year
        $dateInfo['month'] = $month; // current month
        $dateInfo['noOfDays'] = cal_days_in_month(CAL_GREGORIAN, $month, $year); // count of days in month
        $dateInfo['firstDayInMonth'] = $this->getFirstDayInMonth($year, $month); // first day of month

        $this->template->dateInfo = $dateInfo;
        $this->template->next = $this->getNextMonth($year, $month);
        $this->template->prev = $this->getPrevMonth($year, $month);
        $this->template->options = $this->options;
        $this->template->events = $this->events ?? null;

        $wdayMaxLen = (int) Arrays::get($this->options, static::OPT_WDAY_MAX_LEN, PHP_INT_MAX);
        $this->template->wdayMaxLen = $wdayMaxLen;

        $this->template->render();
    }

    protected function getFirstDayInMonth(int $year, int $month): int
    {
        $day = getdate((int) mktime(0, 0, 0, $month, 1, $year));
        $wday = (int) $day['wday'];
        if ($this->firstDay === self::FIRST_SUNDAY) {
            return $wday;
        } elseif ($wday === 0) {
            return 6;
        }
        return $wday - 1;
    }

    protected function getNextMonth(int $year, int $month): array
    {
        $next = [];
        if ($month === 12) {
            $next['month'] = 1;
            $next['year'] = $year + 1;
        } else {
            $next['month'] = $month + 1;
            $next['year'] = $year;
        }
        return $next;
    }

    protected function getPrevMonth(int $year, int $month): array
    {
        $prev = [];
        if ($month === 1) {
            $prev['month'] = 12;
            $prev['year'] = $year - 1;
        } else {
            $prev['month'] = $month - 1;
            $prev['year'] = $year;
        }
        return $prev;
    }

    protected function prepareDate(): void
    {
        if ($this->month === null || $this->year === null) {
            $today = getdate();
            $this->month = $today['mon'];
            $this->year = $today['year'];
        }
        $this->year = (int) $this->year;
        $this->month = (int) $this->month;
    }
}

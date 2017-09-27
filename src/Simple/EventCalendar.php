<?php
declare(strict_types=1);

namespace EventCalendar\Simple;

/**
 * Calendar control with the need to define a translator
 *
 * Also, you can use Texy! syntax in your events, just install Texy! into your project and use it.
 *
 */
class EventCalendar extends \EventCalendar\BasicCalendar
{
    /**
     * Show top navigation for changing months, default <b>true</b>
     */
    const OPT_SHOW_TOP_NAV = 'showTopNav';
    /**
     * Show bottom navigation for changing months, default <b>true</b>
     */
    const OPT_SHOW_BOTTOM_NAV = 'showBottomNav';
    /**
     * maximum length of wday names, by default, full name is used (<b>null</b>)
     */
    const OPT_WDAY_MAX_LEN = 'wdayMaxLen';

    /**
     * @var array default options for calendar - you can change defaults by setOptions()
     */
    protected $options = [
        'showTopNav' => TRUE,
        'showBottomNav' => TRUE,
        'wdayMaxLen' => null
    ];
    
    protected function createTemplate(): \Nette\Application\UI\ITemplate
    {
        /** @var \Nette\Bridges\ApplicationLatte\Template $template */
        $template = parent::createTemplate();
        if (class_exists(\Texy::class)) {
            $texy = new \Texy();
            $template->getLatte()->addFilter('texy', [$texy, 'process']);
        } else {
            $template->getLatte()->addFilter('texy', function ($string) {
                return $string;
            });
        }

        return $template;
    }
    
    protected function getTemplateFile(): string
    {
        return __DIR__ . '/EventCalendar.latte';
    }
}

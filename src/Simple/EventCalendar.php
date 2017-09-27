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

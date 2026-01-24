<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Simple;

use Nexendrie\EventCalendar\AbstractCalendar;

/**
 * Calendar control with the need to define a translator
 *
 * Also, you can use Texy! syntax in your events, just install Texy! into your project and use it.
 */
final class EventCalendar extends AbstractCalendar
{
    protected function createTemplate(): \Nette\Application\UI\Template
    {
        /** @var \Nette\Bridges\ApplicationLatte\Template $template */
        $template = parent::createTemplate();
        $callback = fn($string) => $string;
        if (class_exists(\Texy::class)) {
            $callback = (new \Texy())->process(...);
        }
        $template->getLatte()->addFilter('texy', $callback);

        return $template;
    }

    protected function getTemplateFile(): string
    {
        return __DIR__ . '/EventCalendar.latte';
    }
}

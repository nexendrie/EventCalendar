<?php

declare(strict_types=1);

namespace EventCalendar;

final class Translator implements \Nette\Localization\ITranslator
{

    public function translate($message, ...$parameters): string
    {
        return $message;
    }
}

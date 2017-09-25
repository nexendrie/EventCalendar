<?php
declare(strict_types=1);

namespace EventCalendar\Simple;

class TestTranslator implements \Nette\Localization\ITranslator {
    
    public function translate($message, $count = NULL) {
        return $message;
    }
    
}

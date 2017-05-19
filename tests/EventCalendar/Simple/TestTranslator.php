<?php

namespace EventCalendar\Simple;

class TestTranslator implements \Nette\Localization\ITranslator {
    
    public function translate($message, $count = NULL) {
        return $message;
    }
    
}

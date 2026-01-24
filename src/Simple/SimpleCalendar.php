<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Simple;

use Nexendrie\EventCalendar\AbstractCalendar;
use Nette\Neon\Neon;

/**
 * Simple alternative for calendar control if you don't want to use translator.
 *
 * Specify your language by setting property $language
 *
 * @deprecated Use EventCalendar
 */
final class SimpleCalendar extends AbstractCalendar
{
    public const string LANG_EN = 'en';
    public const string LANG_CZ = 'cz';
    public const string LANG_SK = 'sk';
    public const string LANG_DE = 'de';

    /**
     * text for top link to previous month, default <b><</b>
     */
    public const string OPT_TOP_NAV_PREV = 'topNavPrev';
    /**
     * text for top link to next month, default <b>></b>
     */
    public const string OPT_TOP_NAV_NEXT = 'topNavNext';
    /**
     * text for bottom link to previous month, default <b>Previous month</b>
     */
    public const string OPT_BOTTOM_NAV_PREV = 'bottomNavPrev';
    /**
     * text for bottom link to next month, default <b>Next month</b>
     */
    public const string OPT_BOTTOM_NAV_NEXT = 'bottomNavNext';

    public string $language = self::LANG_EN;

    public function __construct()
    {
        $this->options[self::OPT_TOP_NAV_PREV] = '<';
        $this->options[self::OPT_TOP_NAV_NEXT] = '>';
        $this->options[self::OPT_BOTTOM_NAV_PREV] = 'Previous month';
        $this->options[self::OPT_BOTTOM_NAV_NEXT] = 'Next month';
    }

    public function render(): void
    {
        $this->template->names = $this->getNames($this->language);
        parent::render();
    }

    private function truncateWdays(array $wdays): array
    {
        if ($this->options['wdayMaxLen'] > 0) {
            foreach ($wdays as &$value) {
                $value = mb_substr($value, 0, $this->options['wdayMaxLen'], "UTF-8");
            }
        }
        return $wdays;
    }

    private function getNames(string $lang): array
    {
        $neon = Neon::decode((string) file_get_contents(__DIR__ . '/simpleCalData.neon'));
        if (array_key_exists($lang, $neon)) {
            $wdays = $this->truncateWdays($neon[$lang]['wdays']);
            if ($this->firstDay === self::FIRST_MONDAY) {
                array_push($wdays, array_shift($wdays));
            }
            return ['monthNames' => $neon[$lang]['monthNames'], 'wdays' => $wdays];
        } else {
            throw new \LogicException('Specified language is not supported.');
        }
    }

    protected function getTemplateFile(): string
    {
        return __DIR__ . '/SimpleCalendar.latte';
    }
}

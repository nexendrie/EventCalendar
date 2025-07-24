<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

use Nexendrie\EventCalendar\IEventModel;
use Nette\Utils\Arrays;

/**
 * @property-read array $events
 */
final class GoogleData implements IEventModel
{
    use \Nette\SmartObject;

    public const DATE_FORMAT = 'Y-m-d';

    private array $events = [];
    public string $name;
    public string $description;

    public function addEvent(GoogleEvent $event): void
    {
        $this->events[$event->start->format(self::DATE_FORMAT)][$event->id] = $event;
        $this->events[$event->end->format(self::DATE_FORMAT)][$event->id] = $event;
    }

    protected function getEvents(): array
    {
        return $this->events;
    }

    public function getForDate(int $year, int $month, int $day): array
    {
        return Arrays::get($this->events, $this->numbersToDate($year, $month, $day), []);
    }

    public function isForDate(int $year, int $month, int $day): bool
    {
        return isset($this->events[$this->numbersToDate($year, $month, $day)]);
    }

    private function numbersToDate(int $year, int $month, int $day): string
    {
        /** @var \DateTime $dateTime */
        $dateTime = \DateTime::createFromFormat(self::DATE_FORMAT, $year . '-' . $month . '-' . $day);
        return $dateTime->format(self::DATE_FORMAT);
    }
}

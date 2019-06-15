<?php
declare(strict_types=1);

namespace EventCalendar\Google;

use \EventCalendar\IEventModel;

/**
 * @property string $name
 * @property string $description
 * @property-read array $events
 */
class GoogleData implements IEventModel
{
    use \Nette\SmartObject;
    
    public const DATE_FORMAT = 'Y-m-d';

  /**
   * @var array
   */
    private $events = [];
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    public function addEvent(GoogleEvent $event): void
    {
        $this->events[$event->getStart()->format(self::DATE_FORMAT)][$event->id] = $event;
        $this->events[$event->getEnd()->format(self::DATE_FORMAT)][$event->id] = $event;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function getEvents(): array
    {
        return $this->events;
    }
    
    public function getForDate(int $year, int $month, int $day): array
    {
        return $this->events[$this->numbersToDate($year, $month, $day)];
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

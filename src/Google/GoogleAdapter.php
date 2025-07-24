<?php

declare(strict_types=1);

namespace Nexendrie\EventCalendar\Google;

use Nette\Caching\Cache;
use Nette\Caching\Storages\DevNullStorage;

/**
 * Class for accessing data in Google Calendar by its API
 *
 * Currently, only public calendars are allowed
 *
 * @see https://developers.google.com/google-apps/calendar/v3/reference/events/list API doc for Google Calendar events
 */
final class GoogleAdapter
{
    use \Nette\SmartObject;

    public \DateTime $cacheExpiration;
    public string $searchTerm;
    public bool $showDeleted = false;
    public bool $expandRecurringEvents = false;
    private string $timeMax;
    private string $timeMin;
    private int $year;
    private int $month;

    public \DateTimeZone $timeZone;

    public function __construct(
        private readonly string $calendarId,
        private readonly string $apiKey,
        public Cache $cache = new Cache(new DevNullStorage())
    ) {
    }

    /**
     * Time constraint for events from Google Calendar.
     */
    public function setBoundary(int $year, int $month): GoogleAdapter
    {
        $this->year = $year;
        $this->month = $month;
        $noOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if (isset($this->timeZone)) {
            $min = $year . '-' . $month . '-01T00:00:00Z';
            $max = $year . '-' . $month . '-' . $noOfDays . 'T23:59:59Z';
        } else {
            $min = $year . '-' . $month . '-01T00:00:00Z';
            $max = $year . '-' . $month . '-' . $noOfDays . 'T23:59:59Z';
        }
        $this->timeMax = $max;
        $this->timeMin = $min;
        return $this;
    }

    /**
     * Load events from Google Calendar via API or from cache
     *
     * @throws GoogleApiException
     */
    public function loadEvents(): GoogleData
    {
        return $this->cache->load($this->year . '-' . $this->month, function (&$dependencies): GoogleData {
            $json = json_decode($this->loadByApi());
            if (isset($json->error)) {
                throw new GoogleApiException($json->error->message, $json->error->code);
            }
            $googData = new GoogleData();
            $googData->name = $json->summary;
            $googData->description = $json->description;
            if (isset($json->items)) {
                foreach ($json->items as $item) {
                    $event = new GoogleEvent($item->id);
                    $event->status = $item->status;
                    $event->summary = $item->summary;
                    $event->created = $item->created;
                    $event->updated = $item->updated;
                    $event->htmlLink = $item->htmlLink;
                    $event->start = $item->start->dateTime;
                    $event->end = $item->end->dateTime;
                    if (isset($item->location)) {
                        $event->location = $item->location;
                    }
                    if (isset($item->description)) {
                        $event->description = $item->description;
                    }

                    $googData->addEvent($event);
                }
            }
            if (isset($this->cacheExpiration)) {
                $dependencies[Cache::Expire] = $this->cacheExpiration->getTimestamp();
            }
            return $googData;
        });
    }

    private function loadByApi(): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->prepareUrl()); // @phpstan-ignore argument.type
        /** @var string $response */
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    private function prepareUrl(): string
    {
        $url = 'https://www.googleapis.com/calendar/v3/calendars/';
        $url .= urlencode($this->calendarId);
        $url .= '/events?key=' . $this->apiKey;
        $url .= '&timeMin=' . urlencode($this->timeMin);
        $url .= '&timeMax=' . urlencode($this->timeMax);
        if ($this->expandRecurringEvents) {
            $url .= '&singleEvents=true';
        }
        if (isset($this->timeZone)) {
            $url .= '&timeZone=' . urlencode($this->timeZone->getName());
        }
        if (isset($this->searchTerm)) {
            $url .= '&q=' . urlencode($this->searchTerm);
        }
        if ($this->showDeleted) {
            $url .= '&showDeleted=true';
        }
        return $url;
    }
}

<?php
declare(strict_types=1);

namespace EventCalendar\Google;

use \Nette\Caching\Cache;

/**
 * Class for accessing data in Google Calendar by its API
 *
 * Currently, only public calendars are allowed
 *
 * See API doc for Google Calendar events: https://developers.google.com/google-apps/calendar/v3/reference/events/list
 *
 * @property-write Cache $cache
 * @property-write \DateTime $cacheExpiration
 * @property-write string $searchTerm
 * @property-write \DateTimeZone $timeZone
 */
class GoogleAdapter
{
    use \Nette\SmartObject;
    
    /**
     * @var string
     */
    private $calendarId;
  /**
   * @var string
   */
    private $apiKey;
    /**
     * @var Cache
     */
    private $cache;
    /**
     * @var \DateTime
     */
    private $cacheExpiration;
  /**
   * @var string
   */
    private $searchTerm;
  /**
   * @var bool
   */
    private $showDeleted = false;
  /**
   * @var bool
   */
    private $expandRecurringEvents = false;
  /**
   * @var string
   */
    private $timeMax;
  /**
   * @var string
   */
    private $timeMin;
  /**
   * @var int
   */
    private $year;
  /**
   * @var int
   */
    private $month;

    /**
     * @var \DateTimeZone
     */
    private $timeZone;
    
    public function __construct(string $calendarId, string $apiKey)
    {
        $this->calendarId = $calendarId;
        $this->apiKey = $apiKey;
    }
    
    public function setCache(Cache $cache): GoogleAdapter
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * set expiration for cache
     */
    public function setCacheExpiration(\DateTime $dateTime): GoogleAdapter
    {
        $this->cacheExpiration = $dateTime;
        return $this;
    }

    /**
     * filter events by search term
     */
    public function setSearchTerm(string $searchTerm): GoogleAdapter
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }
    
    public function showDeleted(bool $boolean): GoogleAdapter
    {
        $this->showDeleted = $boolean;
        return $this;
    }

    /**
     * Return recurring events one by one
     */
    public function expandRecurringEvents(bool $boolean): GoogleAdapter
    {
        $this->expandRecurringEvents = $boolean;
        return $this;
    }

    /**
     * Set timezone in which results are returned
     */
    public function setTimeZone(\DateTimeZone $timeZone): GoogleAdapter
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * Time constraint for events from Google Calendar. Used by GoogleCalendar for getting events only for current month.
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
     * @throws GoogleApiException
     */
    public function loadEvents(): GoogleData
    {
        // return from cache
        if (isset($this->cache)) {
            $alreadySaved = $this->cache->load($this->year . '-' . $this->month);
            if ($alreadySaved !== NULL) {
                return $alreadySaved;
            }
        }
        // load via api
        $json = json_decode($this->loadByApi());
        if (isset($json->error)) {
            throw new GoogleApiException($json->error->message, $json->error->code);
        }
        $googData = new GoogleData();
        $googData->setName($json->summary);
        $googData->setDescription($json->description);
        if (isset($json->items)) {
            foreach ($json->items as $item) {
                $event = new GoogleEvent($item->id);
                $event->setStatus($item->status)
                        ->setSummary($item->summary)
                        ->setCreated($item->created)
                        ->setUpdated($item->updated)
                        ->setHtmlLink($item->htmlLink)
                        ->setStart($item->start->dateTime)
                        ->setEnd($item->end->dateTime);
                if (isset($item->location)) {
                    $event->setLocation($item->location);
                }
                if (isset($item->description)) {
                    $event->setDescription($item->description);
                }
                
                $googData->addEvent($event);
            }
        }
        // save loaded data to cache
        if (isset($this->cache)) {
            if (isset($this->cacheExpiration)) {
                $dependencies = [Cache::EXPIRATION => $this->cacheExpiration->getTimestamp()];
            } else {
                $dependencies = null;
            }
            $this->cache->save($this->year . '-' . $this->month, $googData, $dependencies);
        }
        return $googData;
    }

    private function loadByApi(): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $this->prepareUrl());
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
        if (!empty($this->expandRecurringEvents)) {
            $url .= '&singleEvents=true';
        }
        if (isset($this->timeZone)) {
            $url .= '&timeZone=' . urlencode($this->timeZone->getName());
        }
        if (isset($this->searchTerm)) {
            $url .= '&q=' . urlencode($this->searchTerm);
        }
        if (!empty($this->showDeleted)) {
            $url .= '&showDeleted=true';
        }
        return $url;
    }
}

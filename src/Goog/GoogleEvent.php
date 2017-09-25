<?php
declare(strict_types=1);

namespace EventCalendar\Goog;

/**
 * Represent single event from Google Calendar
 * 
 * @property-read int $id
 * @property string $status
 * @property string $htmlLink
 * @property \DateTime|string $created
 * @property \DateTime|string $updated
 * @property string $summary
 * @property string $location
 * @property string $description
 * @property-read string $creator
 * @property \DateTime|string $start
 * @property \DateTime|string $end
 */
class GoogleEvent
{
    use \Nette\SmartObject;
    
    private $id;
    private $status;
    private $htmlLink;
    private $created;
    private $updated;
    private $summary;
    private $location = null;
    private $description = null;
    private $creator;
    private $start;
    private $end;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHtmlLink()
    {
        return $this->htmlLink;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getLocation()
    {
        return $this->location;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getEnd()
    {
        return $this->end;
    }
    
    public function setStatus($status): GoogleEvent
    {
        $this->status = $status;
        return $this;
    }
    
    public function setHtmlLink(string $htmlLink): GoogleEvent
    {
        $this->htmlLink = $htmlLink;
        return $this;
    }

    /**
     * @param string|\DateTime $created
     */
    public function setCreated($created): GoogleEvent
    {
        if (!$created instanceof \DateTime) {
            $created = new \DateTime($created);
        }
        $this->created = $created;
        return $this;
    }

    /**
     * @param string|\DateTime $updated
     */
    public function setUpdated($updated): GoogleEvent
    {
        if (!$updated instanceof \DateTime) {
            $updated = new \DateTime($updated);
        }
        $this->updated = $updated;
        return $this;
    }
    
    public function setSummary(string $summary): GoogleEvent
    {
        $this->summary = $summary;
        return $this;
    }
    
    public function setLocation(string $location): GoogleEvent
    {
        $this->location = $location;
        return $this;
    }
    
    public function setDescription(string $description): GoogleEvent
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string|\DateTime $start
     */
    public function setStart($start): GoogleEvent
    {
        if (!($start instanceof \DateTime)) {
            $start = new \DateTime($start);
        }
        $this->start = $start;
        return $this;
    }

    /**
     * @param string|\DateTime $end
     */
    public function setEnd($end): GoogleEvent
    {
        if (!$end instanceof \DateTime) {
            $end = new \DateTime($end);
        }
        $this->end = $end;
        return $this;
    }

}

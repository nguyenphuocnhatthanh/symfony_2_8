<?php
namespace AppBundle\Model;

class ArticleSearch
{
    // begin of publication range
    protected $dateFrom;

    // end of publication range
    protected $dateTo;

    // published or not
    protected $isPublished;

    protected $title;

    protected $location_last;

    protected $location_lon;

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return ['lon' => $this->getLocationLon(), 'lat' => $this->getLocationLast()];
    }

    /**
     * @return mixed
     */
    public function getLocationLast()
    {
        return $this->location_last;
    }

    /**
     * @param mixed $location_last
     */
    public function setLocationLast($location_last)
    {
        $this->location_last = $location_last;
    }

    /**
     * @return mixed
     */
    public function getLocationLon()
    {
        return $this->location_lon;
    }

    /**
     * @param mixed $location_lon
     */
    public function setLocationLon($location_lon)
    {
        $this->location_lon = $location_lon;
    }



    public function __construct()
    {
        // initialise the dateFrom to "one month ago", and the dateTo to "today"
        $date = new \DateTime();
        $month = new \DateInterval('P1Y');
        $date->sub($month);
        $date->setTime('00','00','00');

        $this->dateFrom = $date;
        $this->dateTo = new \DateTime();
        $this->dateTo->setTime('23','59','59');
    }

    public function setDateFrom($dateFrom)
    {
        if($dateFrom != ""){
            $dateFrom->setTime('00','00','00');
            $this->dateFrom = $dateFrom;
        }

        return $this;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateTo($dateTo)
    {
        if($dateTo != ""){
            $dateTo->setTime('23','59','59');
            $this->dateTo = $dateTo;
        }

        return $this;
    }

    public function clearDates(){
        $this->dateTo = null;
        $this->dateFrom = null;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }

    public function getIsPublished()
    {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
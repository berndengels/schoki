<?php
/**
 * PeriodicDate.php
 *
 * @author    Bernd Engels
 * @created   30.03.19 15:20
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Libs;

use Carbon\Carbon;
use App\Helper\MyDate;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use App\Models\PeriodicEvent;

/**
 * Class EventDateTime
 * @package App\Libs
 */
class EventDateTime extends Carbon
{
    /**
     * @var int
     */
    protected $offsetHours = 5;
//    protected $tz = 'Europe/Berlin';
    /**
     * @var Carbon
     */
    protected $today;
    /**
     * @var Carbon
     */
    protected $validToday;
    /**
     * @var string
     */
    protected $todayWeekday;
    /**
     * @var Carbon
     */
    protected $endDate;
    /**
     * @var Carbon
     */
    protected $endDateIntern;
    /**
     * @var int
     */
    protected $monthLimit = 2;
    /**
     * @var int
     */
    protected $monthLimitIntern = 6;
    /**
     * @var array
     */
    protected $weekDaysByName = [];
    /**
     * @var array
     */
    protected $validInMonthKeys = ['first','second','third','last'];
    /**
     * @var array
     */
    protected $validWeekKeys = [1,2,3,4];

    /**
     * EventDateTime constructor.
     * @param null $time
     * @param null $tz
     */
    public function __construct($time = null, $tz = null)
    {
        $this->today    		= MyDate::getToday();
        $this->validToday    	= MyDate::getUntilValidDate();
        $this->todayWeekday    	= $this->today->copy()->subHours($this->offsetHours)->format('l');
        $this->endDate  		= $this->today->copy()->addMonth($this->monthLimit);
        $this->endDateIntern    = $this->today->copy()->addMonth($this->monthLimitIntern);
        $this->weekDaysByName   = array_flip(parent::$days);
    }

    /**
     * @param $positionSearchKey
     * @param $weekday
     * @param bool $formated
     * @param bool $isPublic
     * @return array|null
     */
    public function getPeriodicEventDates($positionSearchKey, $weekday, $formated = true, $isPublic = false)
    {
        $weekday = ucfirst($weekday);
        $dates = null;
        if( in_array((int) $positionSearchKey, $this->validWeekKeys) ) {
            $dates = $this->getPeriodicWeekDates($weekday, $positionSearchKey, $formated, $isPublic);
        } elseif(in_array($positionSearchKey, $this->validInMonthKeys)) {
            $dates = $this->getPeriodicInMonth($weekday, $positionSearchKey, $formated, $isPublic);
        }

        return $dates;
    }

    /**
     * @param $weekday
     * @param int $weekInterval
     * @param bool $formated
     * @param bool $isPublic
     * @return array
     */
    public function getPeriodicWeekDates($weekday, $weekInterval = 1, $formated = true, $isPublic = false)
    {
        $today = $this->today->copy()->subHours($this->offsetHours);
        if($weekday === $this->todayWeekday) {
            $start  = $today;
        } else {
            $start  = $today->next($this->weekDaysByName[ucfirst($weekday)]);
        }
        $arr = $formated ? [$start->format('Y-m-d')] : [$start];
        /**
         * @var $date Carbon
         */
        do {
            $date = $start->addWeeks($weekInterval);
            $arr[] = $formated ? $date->format('Y-m-d') : $date;
        }
        while ( $date->lt($isPublic ? $this->endDate : $this->endDateIntern) );
        return $arr;
    }

    /**
     * @param $weekday
     * @param $position
     * @param bool $formated
     * @param bool $isPublic
     * @return array
     */
    public function getPeriodicInMonth($weekday, $position, $formated = true, $isPublic = false) {
        $wd     = $this->weekDaysByName[ucfirst($weekday)];
        $start  = $this->getPositionedDate($this->today->copy(), $wd, $position)
            ->addHours(23)
            ->addMinutes(59)
            ->addSeconds(59)
            ->copy()
        ;

        $validStart = $start->gte($this->today->copy()->subHours($this->offsetHours));
        $arr        = $validStart ? [$formated ? $start->format('Y-m-d') : $start] : [];
        /**
         * @var $date Carbon
         */
        do {
            $date = $start->addMonth();
            $date = $this->getPositionedDate($date, $wd, $position);
            $arr[] = $formated ? $date->format('Y-m-d') : $date;
        }
        while ( $date->lt($isPublic ? $this->endDate : $this->endDateIntern) );
        return $arr;
    }

    /**
     * @param $date
     * @param $wd
     * @param $position
     * @return Carbon
     */
    public function getPositionedDate($date, $wd, $position) {
        switch($position) {
            case 'first':
                return $date->copy()->firstOfMonth($wd);
            case 'second':
                return $date->copy()->nthOfMonth(2, $wd);
            case 'third':
                return $date->copy()->nthOfMonth(3, $wd);
            case 'last':
                return $date->copy()->lastOfMonth($wd);
        }
        return $date;
    }
}

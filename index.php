<?php

require_once 'vendor/autoload.php';
use Carbon\Carbon;

class CustomCalendar {

  /**
   * @var string
   */
  protected $date;
  /**
   * @var int
   */
  protected $month_sum;
  /**
   * @var string
   */
  protected $start_date;

  /**
   * CustomCalendar constructor.
   * @param string $date
   * @param int $month_sum
   * @param string $start_date
   */
  public function __construct(string $date = '', int $month_sum = 13, string $start_date = '1.01.1990') {
    $this->date = $date;
    $this->month_sum = $month_sum;
    $this->start_date = $start_date;
  }

  /**
   * @return int
   */
  public function day_name() {
    $date = Carbon::parse($this->start_date)->startOfDay();
    $date2 = Carbon::parse($this->date)->startOfDay();

    $date->addDay($this->get_all_days());

    if ($date2->year == 1990) {
      return 'please enter a date longer than 1990';
    }
    else {
      return $date->format('l');
    }
  }

  /**
   * @return int
   */
  protected function get_all_days():int {

    $date = Carbon::parse($this->date)->startOfDay();
    $all_days = $this->get_days_in_month($date->month);
    $years = $this->get_years($date->year);
    $leap_years = $this->get_leap_years($date->year);
    $days = ((($this->month_sum * 21) + 7) * $years) - $leap_years;
    $days += ($all_days + $date->day);

    return $days;
  }

  /**
   * @param int|NULL $month
   * @return int
   */
  protected function get_days_in_month(int $month = NULL):int {
    if ($month === NULL) {
      return;
    }

    $month_sum = intval($month / 2);

    if ($month != 1) {
      if ($month % 2 == 0) {
        $days = ($month * 21) + $month_sum;
      }
      else {
        $days = ($month * 21) + ++$month_sum;
      }
    }
    else {
      $days = 0;
    }

    return $days;
  }

  /**
   * @param int|NULL $year
   * @return int
   */
  protected function get_leap_years(int $year = NULL):int {
    if ($year === NULL) {
      return;
    }

    $start_point = Carbon::parse($this->start_date)->startOfDay();

    $leap_years = 0;
    for ($i = $start_point->year; $i <= $year; $i++) {
      if (date("L", mktime(0, 0, 0, 7, 7, $i))) {
        $leap_years++;
      }
    }

    return $leap_years;
  }

  /**
   * @param int|NULL $year
   * @return int
   */
  protected function get_years(int $year = NULL):int {
    if ($year === NULL) {
      return;
    }

    $start_point = Carbon::parse($this->start_date)->startOfDay();
    return $year - $start_point->year;
  }
}

print (new CustomCalendar('17.11.2013'))->day_name();

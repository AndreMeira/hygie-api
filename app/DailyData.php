<?php

namespace App;

use App\DailyDataDay;
use Illuminate\Database\Eloquent\Model;

class DailyData extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'start', 'open', 'user_id'
  ];

  public function getAverage() {
    return [
      "calories" => (int) $this->getAverageFor("calories"),
      "proteins" => (int) $this->getAverageFor("proteins"),
      "carbs"    => (int) $this->getAverageFor("carbs"),
      "fat"      => (int) $this->getAverageFor("fat"),
      "weight"   => (int) $this->getAverageFor("weight"),
    ];
  }

  public function getAverageFor($name) {
    $i = 0;
    $acc = 0;
    foreach ($this->dailyDataDays as $key => $day) {
      $acc += $day->{$name};
      $i = $day->{$name} ? $i + 1 : $i;
    }

    return $i ? $acc / $i : null;
  }

  public function getDailyDataDay($day) {
    return $this->getDailyDataDays()->where("day_number", $day)->last();
  }

  public function dailyDataDays() {
    return $this->hasMany('App\DailyDataDay');
  }

  /**
   * Get the user body params
   */
  public function getDailyDataDays() {
    return $this->dailyDataDays;
    // return DailyDataDay::where(
    //   "daily_data_id", $this->id
    // )->get();
  }

  public function createDailyDataDay() {
    DailyDataDay::where(
      "daily_data_id", $this->id
    )->delete();

    foreach (range(1, 7) as $day) {
      DailyDataDay::create([
        "day_number"    => $day,
        "daily_data_id" => $this->id
      ]);
    }
  }
}

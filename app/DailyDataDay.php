<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyDataDay extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'day_number', 'daily_data_id'
  ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBodyFat extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'waist', 'neck', 'hips', 'user_id'
  ];

  public function user() {
    $this->belongsTo('App\User');
  }
}

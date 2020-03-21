<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'firstname', 'lastname', 'user_id'
  ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
  /**
   * Get the user body params
   */
  public function answers() {
      return $this->hasOne('App\SurveyAnswer');
  }

  public function getAnswers() {
      return $this->answers()->where("active", 1)->get();
  }
}

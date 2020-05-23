<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
  /**
   * Get the user body params
   */
  public function results() {
      return $this->hasOne('App\SurveyResult');
  }

  /**
   * Get the user body params
   */
  public function questions() {
      return $this->hasOne('App\SurveyQuestion');
  }

  public function getQuestions() {
      return $this->questions()->where("active", 1)->get();
  }
}

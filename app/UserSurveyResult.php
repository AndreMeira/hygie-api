<?php

namespace App;

use App\Survey;
use App\UserSurveyResponse;
use Illuminate\Database\Eloquent\Model;

class UserSurveyResult extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'survey_id', 'user_id', 'completed_at', 'score', "conclusion", "title"
  ];

  public function survey() {
      return $this->belongsTo(Survey::class);
  }

  public function responses() {
      return $this->hasMany(UserSurveyResponse::class);
  }
}

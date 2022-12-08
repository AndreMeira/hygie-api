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

  /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'completed_at',
    ];

  public function survey() {
      return $this->belongsTo(Survey::class);
  }

  public function responses() {
      return $this->hasMany(UserSurveyResponse::class);
  }
}

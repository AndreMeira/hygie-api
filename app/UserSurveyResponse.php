<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SurveyAnswer;

class UserSurveyResponse extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'survey_id',
      'user_id',
      'score',
      'survey_question_id',
      'survey_answer_id',
      'user_survey_result_id',
      'comment'
  ];

  public function answer() {
      return $this->belongsTo(SurveyAnswer::class);
  }
}

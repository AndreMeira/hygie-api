<?php

namespace App\Services\Computing;

use \App\Survey;
use \App\SurveyResult;
use \App\SurveyAnswer;
use \App\SurveyQuestion;
use \App\UserSurveyResult;
use \App\UserSurveyResponse;

class SurveyResponse {

  /**
   *
   */
  public function saveSurvey($user, $post) {
      $survey     = Survey::find($post["id"]);
      $userSurvey = $this->saveUserSurvey($user, $post);

      foreach ($post["questions"] as $question) {
          $this->saveUserAnswers($user, $userSurvey, $question);
      }

      return $userSurvey;
  }

  /**
   *
   */
  public function saveUserSurvey($user, $post) {
      $score  = $this->computeScore($post["questions"]);
      $result = $this->loadResult($post["id"], $score);
      
      return UserSurveyResult::create([
          "survey_id"    => $post["id"],
          "user_id"      => $user->id,
          "completed_at" => date("Y-m-d H:i:s"),
          "score"        => $score,
          "conclusion"   => $result->conclusion,
          "title"        => $result->title,
      ]);
  }

  /**
   *
   */
  public function saveUserAnswers($user, $userSurvey, $question) {
      // $allAnswers = $this->getAnswerIdsFromQuestions($question);
      $allAnswers = $this->loadAnswersAndIndexById($question["response"]);

      $response = array_filter((array) $question["response"]);

      foreach ($response as $response) {
          $answer = $allAnswers[$response];
          $this->saveUserAnswersForQuestion(
            $userSurvey, $answer, (string) @$question["comment"]
          );
      }
  }

  /**
   *
   */
  public function saveUserAnswersForQuestion($userSurvey, $answer, $comment) {
      UserSurveyResponse::create([
          "survey_answer_id"   => $answer->id,
          "score"              => $answer->score,
          "survey_id"          => $answer->survey_id,
          "user_id"            => $userSurvey->user_id,
          "survey_question_id" => $answer->survey_question_id,
          "user_survey_result_id" => $userSurvey->id,
          "comment"           => $comment
      ]);
  }

  /**
   *
   */
  public function computeScore($questions) {
      $score      = 0;
      $allAnswers = $this->getAnswerIdsFromQuestions($questions);
      $answers    = SurveyAnswer::whereIn("id", $allAnswers)->get();

      foreach ($answers as $answer) {
          $score += $answer->score;
      }

      return $score;
  }

  /**
   *
   */
  public function loadAnswersAndIndexById($allAnswers) {
      $indexed = [];
      $answers = SurveyAnswer::whereIn("id", $allAnswers)->get();
      foreach ($answers as $key => $answer) {
          $indexed[$answer->id] = $answer;
      }

      return $indexed;
  }


  public function loadResult($id, $score) {
      return SurveyResult::where("survey_id", $id)
        ->where('min_score', '<=',  $score)
        ->where('max_score', '>',  $score)
        ->first();
  }

  /**
   *
   */
  protected function getAnswerIdsFromQuestions($questions) {
    $allAnswers = [];

    foreach ($questions as $question) {
          $response = array_filter((array) @$question["response"]);
          $allAnswers = array_merge($allAnswers, $response);

    }
    return $allAnswers;
  }
}

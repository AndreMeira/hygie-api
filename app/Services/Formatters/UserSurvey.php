<?php

namespace App\Services\Formatters;

use App\SurveyQuestion;
use App\SurveyAnswer;

class UserSurvey {

  /**
   *
   */
  public function formatResponses($surveyResult) {
      $responseIndexed = $this->indexResponses($surveyResult->responses);
      $questions = $this->loadQuestions(array_keys($responseIndexed));

      $result = [];

      foreach ($questions as $question) {
        $comment = $responseIndexed[$question->id][0]->comment;
        $result[] = [
            "comment"  => $comment,
            "label"    => $question->label,
            "response" => array_map(function ($e) {
                return SurveyAnswer::find($e->survey_answer_id)->label;
            }, $responseIndexed[$question->id])
        ];
      }

      return [
        "title"        => $surveyResult->survey->title,
        "score"        => $surveyResult->score,
        "result"       => $surveyResult->title,
        "conclusion"   => $surveyResult->conclusion,
        "completed_at" => $surveyResult->completed_at,
        "questions"    => $result
      ];
  }

  /**
   *
   */
  public function loadQuestions($ids) {
      return SurveyQuestion::whereIn("id", $ids)->get();
  }

  /**
   *
   */
  public function indexResponses($responses) {
      $responseIndexed = [];
      foreach ($responses as $response) {
          $responseIndexed[$response->survey_question_id][] = $response;
      }

      return $responseIndexed;
  }



  /**
   *
   */
  public function formatResult(\App\User $user) {
      $result = ["results" => []];

      foreach ($user->getSurveyResults() as $surveyResult) {
          $result["results"][] = $this->formatUserResult(
              $surveyResult
          );
      }

      return $result;
  }

  public function formatUserResult($surveyResult) {

      return $surveyResult ? [
          "id"           => $surveyResult->id,
          "category"     => $surveyResult->survey->category,
          "title"        => $surveyResult->survey->title,
          "subtitle"     => $surveyResult->title,
          "score"        => $surveyResult->score,
          "conclusion"   => $surveyResult->conclusion,
          "completed_at" => $surveyResult->completed_at,
      ] : null;
  }

  public function formatSurvey($user, $survey) {
      return [
         "id"              => $survey->id,
         "category"        => $survey->category,
         "categoryTitle"   => $survey->title,
         "currentQuestion" => 0,
         "questions"       => $this->formatQuestions(
              $user, $survey->getQuestions()
          )
      ];
  }

  public function formatQuestions($user, $questions) {
      $result = [];

      foreach ($questions as $key => $question) {
          $result[] = $this->formatQuestion($user, $question);
      }

      return $result;
  }

  public function formatQuestion($user, $question) {
      return [
        "id"       => $question->id,
        "label"    => $question->label,
        "multiple" => $question->multiple_choice,
        "comment"  => "",
        "previous_comment" => "",
        "previous_answer"  => [],
        "response"         => [],
        "answers"          => $this->formatAnswers(
            $user, $question->getAnswers()
        )
      ];
  }

  public function formatAnswers($user, $answers) {
    $result = [];

    foreach ($answers as $key => $answer) {
        $result[] = $this->formatAnswer($user, $answer);
    }

    return $result;
  }

  public function formatAnswer($user, $answer) {
      return [
        "id"    => $answer->id,
        "label" => $answer->label
      ];
  }
}

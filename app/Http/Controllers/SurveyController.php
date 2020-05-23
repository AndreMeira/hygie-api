<?php

namespace App\Http\Controllers;

use PDF;
use App\Survey;
use App\UserSurveyResult;
use App\Services\Formatters\UserSurvey;
use App\Services\Computing\SurveyResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
  /**
   *
   */
  public function __construct() {
      $this->formatter = new UserSurvey();
      $this->responseComputing = new SurveyResponse();
  }

  /**
   *
   */
  public function me(Request $req) {

    return response()->json(
        $this->formatter->formatResult($req->user())
    );
  }

  /**
   *
   */
  public function survey(Request $req, $cat) {
    $user = $req->user();
    $survey = Survey::where("active", 1)
        ->where("category", $cat)
        ->get()->first();

    return response()->json(
        $survey ? $this->formatter->formatSurvey($user, $survey) : null
    );
  }

  /**
   *
   */
  public function saveSurvey(Request $req) {
    $user = $req->user();
    $result = $this->responseComputing->saveSurvey(
      $user, $req->all()
    );
    return response()->json(
        $this->formatter->formatUserResult($result)
    );
  }

  public function previousResultAndAnswers(Request $req, $id) {
      $user = $req->user();
      $result = UserSurveyResult::find($id);

      $this->authorize("view", $result);

      return response()->json(
          $result ? $this->formatter->formatResponses($result) : null
      );
  }

  public function downloadResult(Request $req, $id) {
      $user   = $req->user();
      $result = UserSurveyResult::find($id);

      // $this->authorize("view", $result);

      $data = $this->formatter->formatResponses($result);
      $pdf  = PDF::loadView('survey-result', [
        "result" => (object) $data
      ]);

      return $pdf->download('resultat.pdf');
  }
}

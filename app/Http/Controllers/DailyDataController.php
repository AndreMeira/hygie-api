<?php

namespace App\Http\Controllers;

use App\User;
use App\DailyData;
use App\Services\Computing\CaloriesRecommandations;
use App\Services\Computing\BodyFat as BodyFatComputing;
use App\Services\Formatters\User as UserFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DailyDataController extends Controller
{
  // public function __construct(UserFormatter $formatter) {
  public function __construct() {
      $this->caloriesRecommandation = new CaloriesRecommandations();
      $this->bodyFatComputing = new BodyFatComputing();
      $this->formatter = new UserFormatter($this->bodyFatComputing);
  }

  public function getDailyData(Request $req) {
      $user = $req->user();
      if ($dailyData = $user->getLastDailyData()) {
          return $this->getDailyDataById($req, $dailyData->id);
      }
      return response()->json(null);
  }

  public function getDailyDataById(Request $req, $id) {
      $user = $req->user();
      $dailyData = $user->getDailyData($id);
      $dailyDataDays = $dailyData->getDailyDataDays();
      $bodyParam = $user->getLastBodyParam();

      $json = $dailyData->toArray() + [
          "days"    => $dailyDataDays->toArray(),
          "average" => $dailyData->getAverage()
      ];
      return response()->json($json);
  }

  public function updateDailyDataDay(Request $req, $day) {
      $user = $req->user();
      $dailyData    = $user->getDailyData($req->input('id'));
      $dailyDataDay = $dailyData->getDailyDataDay($day);

      $dailyDataDay->calories = $req->input('calories');
      $dailyDataDay->carbs    = $req->input('carbs');
      $dailyDataDay->fat      = $req->input('fat');
      $dailyDataDay->proteins = $req->input('proteins');
      $dailyDataDay->weight   = $req->input('weight');
      $dailyDataDay->save();

    return $this->getDailyDataById($req, $dailyData->id);
  }

  public function createDailyData(Request $req) {
    $user = $req->user();
    DailyData::where("user_id", $user->id)->update(["open" => 0]);

    $dailyData = DailyData::create([
      "start" => $req->input('date'),
      "user_id" => $user->id,
      "open" => 1
    ]);

    $dailyData->createDailyDataDay();
    return $this->getDailyDataById($req, $dailyData->id);
  }
}

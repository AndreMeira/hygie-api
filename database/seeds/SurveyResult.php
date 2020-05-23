<?php

use Illuminate\Database\Seeder;

class SurveyResult extends Seeder
{
    const RESULTS = [
      "food" => [[
          "min_score"  => -1000,
          "max_score"  => 7,
          "headline"   => "C’est pas folichon !"
      ], [
          "min_score"  => 7,
          "max_score"  => 40,
          "headline"   => "Les voyants clignotent"
      ], [
          "min_score"  => 40,
          "max_score"  => 1000,
          "headline"   => "Wow !"
      ]],
      "health" => [[
          "min_score"  => -1000,
          "max_score"  => 0,
          "headline"   => "C’est pas folichon !"
      ], [
          "min_score"  => 7,
          "max_score"  => 60,
          "headline"   => "Les voyants clignotent"
      ], [
          "min_score"  => 60,
          "max_score"  => 1000,
          "headline"   => "Wow !"
      ]]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::transaction(function () {
            $this->fillDatabase();
        });
    }

    protected function fillDatabase() {
        foreach (["food", "health"] as $cat) {
            $survey = $this->loadSurvey($cat);
            $this->createResultsOfSurvey($survey);
        }
    }

    protected function loadSurvey($cat) {
        $survey = DB::table("surveys")
          ->where("active", 1)
          ->where("category", $cat)->first();
        return $survey;
    }

    protected function createResultsOfSurvey($survey) {
        $texts = $this->loadTexts($survey->category);
        foreach (self::RESULTS[$survey->category] as $key => $result) {
            $this->createResult($survey, $result, $texts[$key]);
        }
    }

    protected function loadTexts($cat) {
        $texts = file_get_contents(__DIR__."/data/result-$cat.txt");
        $texts = explode("-----------", $texts);

        return array_map(function($e) {
            return trim($e);
        }, $texts);
    }

    protected function createResult($survey, $result, $text) {
        DB::table("survey_results")->insert([
            "max_score"  => $result["max_score"],
            "min_score"  => $result["min_score"],
            "title"      => $result["headline"],
            "survey_id"  => $survey->id,
            "conclusion" => $text
        ]);
    }
}

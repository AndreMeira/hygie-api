<?php

use Illuminate\Database\Seeder;

class ChangeLabel extends Seeder
{
    protected $tr = [
        "youart"                     => "yaourt",
        "matières grasses"           => "matière grasse",
        "Epuisé"                     => "Epuisé(e)",
        "fatigué"                    => "fatigué(e)",
        "stressé"                    => "stressé(e)",
        "lancé"                      => "lancé(e)",
        "assis"                      => "assis(e)",
        "déprimé"                    => "déprimé(e)",
        "soucieux"                   => "soucieux(se)",
        "Si j'ai mal dormi oui"      => "Si j'ai mal dormi oui, sinon non",
        "épanoui et heureux"         => "épanoui(e) et heureux(se)",

        "Coche ce qui te correspond (plusieurs réponses possibles)" => "Coche ce qui te correspond (1 à 6 réponses possibles)",
    ];

    protected $missingLabels = [[
      "survey_id"          => 9,
      "survey_question_id" => 66,
      "label"              => "Céréales raffinées (riz blanc, pâtes blanches, etc.)",
      "score"              => -2,
      "active"             => 1
    ]];

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

    public function fillDatabase() {
      $this->addMissingLabels();
      foreach ($this->tr as $key => $value) {
        $this->changeTextLabel($key, $value);
      }
    }

    protected function addMissingLabels() {
      foreach ($this->missingLabels as $label) {
        DB::table('survey_answers')->insert($label);
      }
    }

    protected function changeTextLabel($key, $value) {
        $this->changeQuestionsLabel($key, $value);
        $this->changeResponsesLabel($key, $value);
    }

    protected function changeQuestionsLabel($key, $value) {
      $rows = DB::table('survey_questions')
        ->where('label', 'like', "%${key}%")
        ->get();

      foreach ($rows as $row) {
          $label = str_replace($key, $value, $row->label);
          DB::table('survey_questions')
            ->where('id', "=", $row->id)
            ->update(['label' => $label]);
      }
    }

    protected function changeResponsesLabel($key, $value  ) {
      $rows = DB::table('survey_answers')
        ->where('label', 'like', "%$key%")
        ->get();

      foreach ($rows as $row) {
          $label = str_replace($key, $value, $row->label);
          DB::table('survey_answers')
            ->where('id', "=", $row->id)
            ->update(['label' => $label]);
      }
    }
}
